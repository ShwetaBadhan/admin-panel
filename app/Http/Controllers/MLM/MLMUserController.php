<?php

namespace App\Http\Controllers\MLM;

use App\Http\Controllers\Controller;
use App\Mail\MlmActivationMail;
use App\Models\MlmUser;
use App\Models\SpillingPreference;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class MLMUserController extends Controller
{
    public function index()
    {
        $users = MlmUser::with(['sponsor', 'tree'])
            ->where('is_deleted', false)
            ->orderBy('created_at', 'desc')
            ->paginate(15);
        
        // ✅ Load active products for order modal
        $products = Product::where('status', 1)
            ->where('stock', '>', 0)
            ->get(['id', 'name', 'sku', 'price', 'discount_price', 'cc_points', 'stock']);
            
        return view('admin.pages.mlm.register-users', compact('users', 'products'));
    }

    public function storeOrder(Request $request)
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
            $totalAmount = 0;
            $totalCC = 0;
            $orderItems = [];

            foreach ($validated['items'] as $item) {
                $product = Product::lockForUpdate()->find($item['product_id']);
                
                if (!$product) {
                    throw new \Exception("Product not found");
                }
                
                if ($product->stock < $item['quantity']) {
                    throw new \Exception("Insufficient stock for {$product->name}. Available: {$product->stock}");
                }

                $price = $product->discount_price ?? $product->price;
                $subtotal = $price * $item['quantity'];
                $ccForItem = $product->cc_points * $item['quantity'];

                $totalAmount += $subtotal;
                $totalCC += $ccForItem;

                $orderItems[] = [
                    'product_id' => $product->id,
                    'quantity' => $item['quantity'],
                    'price' => $price,
                    'cc_points' => $product->cc_points,
                    'status' => 'active',
                ];

                $product->decrement('stock', $item['quantity']);
            }

            $order = Order::create([
                'user_id' => $validated['user_id'],
                'package_id' => null,
                'order_date' => now(),
                'total_amount' => $totalAmount,
                'total_cc_points' => $totalCC,
                'status' => 'COMPLETED',
                'order_type' => 'SELF',
                'refund_policy' => 'WITHIN_30_DAYS',
                'payment_mode' => $validated['payment_mode'],
                'note' => "Order created by admin for {$user->user_name}",
            ]);

            foreach ($orderItems as $itemData) {
                $order->items()->create($itemData);
            }

            DB::commit();
            
            return back()->with('success', "✅ Order created successfully! Order ID: {$order->id} | CC Points: {$totalCC}");

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => $e->getMessage()])->withInput();
        }
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'sponsor_username' => 'required|string|exists:mlm_users,user_name',
            'user_name' => 'required|string|max:255|unique:mlm_users,user_name',
            'first_name' => 'required|string|max:100',
            'last_name' => 'nullable|string|max:100',
            'email' => 'required|email|max:255|unique:mlm_users,email',
            'phone' => 'required|digits:10|unique:mlm_users,phone',
            'password' => 'required|string|min:8|confirmed',
            'commission_percentage' => 'required|in:10,12,14,16,18,20',
        ], [
            'sponsor_username.exists' => 'Sponsor username not found.',
            'commission_percentage.in' => 'Commission must be 10%, 12%, 14%, 16%, 18%, or 20%.',
        ]);

        DB::beginTransaction();
        try {
            $sponsor = MlmUser::where('user_name', $validated['sponsor_username'])
                ->where('is_active', true)
                ->where('is_deleted', false)
                ->firstOrFail();

            $trackId = 'TRK' . date('Y') . strtoupper(Str::random(6)) . time();

            $mlmUser = MlmUser::create([
                'user_name' => $validated['user_name'],
                'track_id' => $trackId,
                'first_name' => $validated['first_name'],
                'last_name' => $validated['last_name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'],
                'password' => Hash::make($validated['password']),
                'sponsor_id' => $sponsor->id,
                'position_in_sponsor_leg' => 'none',
                'membership_type' => 'CUSTOMER',
                'is_active' => false,
                'is_verified' => false,
                'is_deleted' => false,
                'verification_token' => Str::random(60),
                'verification_expires' => now()->addHours(24),
                'commission_percentage' => $validated['commission_percentage'],
            ]);

            SpillingPreference::create([
                'mlm_user_id' => $mlmUser->id,
                'preference' => 'HOLDING_TANK',
            ]);

            $activationUrl = route('mlm.activate', ['token' => $mlmUser->verification_token]);

            try {
                Mail::to($mlmUser->email)->send(new MlmActivationMail($mlmUser, $activationUrl));
                Log::info("Activation email sent to {$mlmUser->email}");
            } catch (\Exception $e) {
                Log::error("Email failed for {$mlmUser->email}: " . $e->getMessage());
            }

            DB::commit();

            return redirect()->route('mlm-users.index')
                ->with('success', "User registered! Activation email sent to {$mlmUser->email}");

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('MLM Registration Failed', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return back()->withErrors(['error' => 'Registration failed: ' . $e->getMessage()])->withInput();
        }
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:100',
            'last_name' => 'nullable|string|max:100',
            'email' => 'required|email|max:255|unique:mlm_users,email,'.$id,
            'phone' => 'required|string|max:20|unique:mlm_users,phone,'.$id,
            'is_active' => 'nullable|boolean',
            'is_verified' => 'nullable|boolean',
            'password' => 'nullable|string|min:8|confirmed',
            'commission_percentage' => 'sometimes|required|in:10,12,14,16,18,20',
        ]);

        $mlmUser = MlmUser::findOrFail($id);
        
        $updateData = [
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'] ?? null,
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'is_active' => $validated['is_active'] ?? $mlmUser->is_active,
            'is_verified' => $validated['is_verified'] ?? $mlmUser->is_verified,
        ];

        if (isset($validated['commission_percentage'])) {
            $updateData['commission_percentage'] = $validated['commission_percentage'];
        }

        if (isset($validated['is_verified']) && $validated['is_verified'] && !$mlmUser->is_verified) {
            $updateData['verification_token'] = null;
            $updateData['verification_expires'] = null;
        }

        if (!empty($validated['password'])) {
            $updateData['password'] = Hash::make($validated['password']);
        }

        $mlmUser->update($updateData);

        return redirect()->route('mlm-users.index')
            ->with('success', "User updated: {$mlmUser->user_name}");
    }

    public function destroy($id)
    {
        $mlmUser = MlmUser::findOrFail($id);
        $mlmUser->update(['is_deleted' => true, 'is_active' => false]);
        return redirect()->route('mlm-users.index')->with('success', "User soft-deleted: {$mlmUser->user_name}");
    }

    public function resendActivation($id)
    {
        try {
            $mlmUser = MlmUser::findOrFail($id);
            if ($mlmUser->is_verified) {
                return response()->json(['success' => false, 'message' => 'User already activated.']);
            }
            $mlmUser->update([
                'verification_token' => Str::random(60),
                'verification_expires' => now()->addHours(24),
            ]);
            $activationUrl = route('mlm.activate', ['token' => $mlmUser->verification_token]);
            Mail::to($mlmUser->email)->send(new MlmActivationMail($mlmUser, $activationUrl));
            return response()->json(['success' => true, 'message' => 'Activation email sent.']);
        } catch (\Exception $e) {
            Log::error('Resend activation failed', ['error' => $e->getMessage()]);
            return response()->json(['success' => false, 'message' => 'Failed to send email.'], 500);
        }
    }

    public function activate(Request $request, $token)
    {
        $user = MlmUser::where('verification_token', $token)
            ->where('verification_expires', '>', now())
            ->first();

        if (!$user) {
            return view('admin.pages.mlm.activation-error', ['message' => 'Invalid or expired activation link. Please contact admin.']);
        }

        $user->update([
            'is_verified' => true,
            'is_active' => true,
            'verification_token' => null,
            'verification_expires' => null,
        ]);

        return view('admin.pages.mlm.activation-success', ['userName' => $user->user_name, 'email' => $user->email]);
    }

    public function recycleBin()
    {
        $deletedUsers = MlmUser::with(['sponsor'])
            ->where('is_deleted', true)
            ->orderBy('updated_at', 'desc')
            ->paginate(15);
        return view('admin.pages.mlm.recycle-bin', compact('deletedUsers'));
    }

    public function restore($id)
    {
        try {
            $mlmUser = MlmUser::findOrFail($id);
            $mlmUser->update(['is_deleted' => false, 'is_active' => $mlmUser->is_active]);
            $spillingPreference = SpillingPreference::where('mlm_user_id', $id)->first();
            if ($spillingPreference && ($spillingPreference->is_deleted ?? false)) {
                $spillingPreference->update(['is_deleted' => false]);
            }
            return redirect()->back()->with('success', "✅ User restored: {$mlmUser->user_name}");
        } catch (\Exception $e) {
            Log::error('Restore user failed', ['error' => $e->getMessage()]);
            return redirect()->back()->with('error', "❌ Failed to restore user: {$e->getMessage()}");
        }
    }

    public function permanentDelete($id)
    {
        try {
            $mlmUser = MlmUser::findOrFail($id);
            $userName = $mlmUser->user_name;
            SpillingPreference::where('mlm_user_id', $id)->delete();
            $mlmUser->delete();
            return redirect()->back()->with('success', "🗑️ User permanently deleted: {$userName}");
        } catch (\Exception $e) {
            Log::error('Permanent delete failed', ['error' => $e->getMessage()]);
            return redirect()->back()->with('error', "❌ Failed to permanently delete: {$e->getMessage()}");
        }
    }

    public function bulkRestore(Request $request)
    {
        $validated = $request->validate(['user_ids' => 'required|array', 'user_ids.*' => 'exists:mlm_users,id']);
        $count = 0;
        foreach ($validated['user_ids'] as $id) {
            $user = MlmUser::find($id);
            if ($user && $user->is_deleted) {
                $user->update(['is_deleted' => false]);
                $count++;
            }
        }
        return redirect()->back()->with('success', "✅ {$count} user(s) restored successfully");
    }

    public function bulkPermanentDelete(Request $request)
    {
        $validated = $request->validate(['user_ids' => 'required|array', 'user_ids.*' => 'exists:mlm_users,id']);
        $count = 0;
        foreach ($validated['user_ids'] as $id) {
            $user = MlmUser::find($id);
            if ($user) {
                SpillingPreference::where('mlm_user_id', $id)->delete();
                $user->delete();
                $count++;
            }
        }
        return redirect()->back()->with('success', "🗑️ {$count} user(s) permanently deleted");
    }

    public static function getCommissionAmount(int $percentage): float
    {
        return ($percentage === 20) ? 200.00 : 100.00;
    }
}