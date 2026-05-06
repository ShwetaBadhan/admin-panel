<?php
namespace App\Http\Controllers\MLM;

use App\Http\Controllers\Controller;
use App\Models\MlmUser;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Services\PayoutService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MLMOrderController extends Controller
{
    public function index($userId)
    {
        $user = MlmUser::findOrFail($userId);
        $orders = Order::with('items')
            ->where('user_id', $userId)
            ->orderBy('order_date', 'desc')
            ->paginate(15);
        return view('admin.pages.mlm.order-history', compact('user', 'orders'));
    }

    public function create($userId)
    {
        $user = MlmUser::findOrFail($userId);
        $products = Product::where('status', 1)->where('stock', '>', 0)->get();
        return view('admin.pages.mlm.create-order', compact('user', 'products'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:mlm_users,id',
            'payment_mode' => 'required|in:cash,online,upi',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        DB::beginTransaction();
        try {
            $user = MlmUser::findOrFail($validated['user_id']);
            
            // 🔒 Lifetime limit check
            $lifetimePurchased = OrderItem::whereHas('order', fn($q) => 
                $q->where('user_id', $user->id)->where('status', 'COMPLETED')
            )->sum('quantity');
            
            $totalItemsInCart = collect($validated['items'])->sum('quantity');
            if (($lifetimePurchased + $totalItemsInCart) > 40) {
                throw new \Exception("❌ Lifetime limit exceeded. Maximum 40 products allowed.");
            }

            // 🎯 Calculate totals
            $ccRate = \App\Models\CCSetting::getActiveRate();
            $totalAmount = 0; $totalCC = 0; $totalQuantity = 0;
            $orderItems = [];

            foreach ($validated['items'] as $item) {
                $product = Product::lockForUpdate()->find($item['product_id']);
                if (!$product || $product->stock < $item['quantity']) {
                    throw new \Exception("Insufficient stock for {$product->name}");
                }

                $price = $product->discount_price ?? $product->price;
                $totalAmount += $price * $item['quantity'];
                $totalCC += $product->cc_points * $item['quantity'];
                $totalQuantity += $item['quantity'];

                $orderItems[] = [
                    'product_id' => $product->id,
                    'quantity' => $item['quantity'],
                    'price' => $price,
                    'cc_points' => $product->cc_points,
                    'status' => 'active',
                ];
                $product->decrement('stock', $item['quantity']);
            }

            // 📦 Create Order
            $order = Order::create([
                'user_id' => $user->id,
                'order_date' => now(),
                'total_amount' => $totalAmount,
                'total_cc_points' => $totalCC,
                'status' => 'COMPLETED',
                'order_type' => 'SELF',
                'payment_mode' => $validated['payment_mode'],
            ]);
            $order->items()->createMany($orderItems);

            // 💰 Direct Income Calculation
            $ccValueInRupees = $totalCC * $ccRate;
            $bonusPerBottle = ($user->commission_percentage == 20) ? 200 : 100;
            $extraBonus = $totalQuantity * $bonusPerBottle;
            $totalDirectIncome = $ccValueInRupees + $extraBonus;

            $balance = \App\Models\PayoutBalance::firstOrNew(['mlm_user_id' => $user->id]);
            $balance->available_balance += $totalDirectIncome;
            $balance->total_earned += $totalDirectIncome;
            $balance->cc_balance += $totalCC;
            $balance->save();

            \App\Models\PayoutTransaction::create([
                'mlm_user_id' => $user->id,
                'type' => 'direct_income',
                'cc_amount' => $totalCC,
                'currency_amount' => $totalDirectIncome,
                'status' => 'credited',
                'description' => "Direct income from order #{$order->id}",
                'meta' => ['cc_rate' => $ccRate, 'bonus_per_bottle' => $bonusPerBottle],
            ]);

            // 🔄 Auto-upgrade tier
            $newLifetimeTotal = $lifetimePurchased + $totalItemsInCart;
            if ($newLifetimeTotal >= 20 && $user->commission_percentage < 20) {
                $user->update(['commission_percentage' => 20]);
            }
            if ($newLifetimeTotal >= 40) {
                $balance->is_payout_eligible = true;
                $balance->save();
            }

            // 🎯 PROCESS PAIR MATCHING (Via Service)
            $payoutService = new PayoutService();
            $payoutService->processPairMatching($user, $totalCC);

            DB::commit();
            return back()->with('success', "✅ Order #{$order->id} created! Direct: ₹".number_format($totalDirectIncome, 2));

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => $e->getMessage()])->withInput();
        }
    }
}