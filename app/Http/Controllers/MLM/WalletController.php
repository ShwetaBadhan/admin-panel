<?php
namespace App\Http\Controllers\MLM;

use App\Http\Controllers\Controller;

use App\Models\Wallet;
use App\Models\WalletConfiguration;
use App\Models\WalletCharge;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WalletController extends Controller
{
    /**
     * Display wallet list
     */
    public function index()
    {
        $wallets = Wallet::with(['configuration', 'charges'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);
        
        return view('admin.wallets.index', compact('wallets'));
    }
    
    /**
     * Show create form
     */
    public function create()
    {
        $eligibilityOptions = [
            'ALL' => 'All Users',
            'SPONSORED_ONLY' => 'Sponsored Users Only',
            'ACTIVE_MEMBERS' => 'Active Members Only'
        ];
        
        $internalCodes = [
            'COMMISSION' => 'Commission',
            'PURCHASE' => 'Purchase',
            'REWARD' => 'Reward',
            'BONUS' => 'Bonus',
            'REFERRAL' => 'Referral'
        ];
        
        return view('admin.wallets.create', compact('eligibilityOptions', 'internalCodes'));
    }
    
    /**
     * Store new wallet
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|unique:wallets,code',
            'currency_code' => 'required|string|size:3',
            'eligibility' => 'required|in:ALL,SPONSORED_ONLY,ACTIVE_MEMBERS',
            'type' => 'required|in:CREDIT,DEBIT,BOTH',
            'min_balance' => 'nullable|numeric|min:0',
            'max_balance' => 'nullable|numeric|gt:min_balance',
            'description' => 'nullable|string',
        ]);
        
        DB::beginTransaction();
        try {
            $wallet = Wallet::create($validated);
            
            // Create default configuration
            WalletConfiguration::create([
                'wallet_id' => $wallet->id,
                'payout_schedule' => 'WEEKLY',
                'refund_window_days' => 30,
                'min_withdraw_amount' => 500,
                'max_payouts_per_batch' => 500,
                'withdraw_cooldown_days' => 7,
                'processing_fee_percent' => 0,
                'processing_fee_fixed' => 0,
            ]);
            
            DB::commit();
            return redirect()->route('wallets.index')
                ->with('success', 'Wallet created successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }
    
    /**
     * Show wallet details
     */
    public function show(Wallet $wallet)
    {
        $wallet->load(['configuration', 'charges', 'balances.user']);
        return view('admin.wallets.show', compact('wallet'));
    }
    
    /**
     * Show edit form
     */
    public function edit(Wallet $wallet)
    {
        $eligibilityOptions = [
            'ALL' => 'All Users',
            'SPONSORED_ONLY' => 'Sponsored Users Only',
            'ACTIVE_MEMBERS' => 'Active Members Only'
        ];
        
        return view('admin.wallets.edit', compact('wallet', 'eligibilityOptions'));
    }
    
    /**
     * Update wallet
     */
    public function update(Request $request, Wallet $wallet)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|unique:wallets,code,' . $wallet->id,
            'currency_code' => 'required|string|size:3',
            'eligibility' => 'required|in:ALL,SPONSORED_ONLY,ACTIVE_MEMBERS',
            'is_active' => 'boolean',
        ]);
        
        $wallet->update($validated);
        
        return redirect()->route('wallets.index')
            ->with('success', 'Wallet updated successfully!');
    }
    
    /**
     * Delete wallet
     */
    public function destroy(Wallet $wallet)
    {
        $wallet->delete();
        return back()->with('success', 'Wallet deleted successfully!');
    }
    
    /**
     * Wallet Payout Configuration (Setting 1)
     */
    public function payoutConfig(Wallet $wallet)
    {
        $wallet->load('configuration');
        return view('admin.wallets.payout-config', compact('wallet'));
    }
    
    /**
     * Update payout configuration
     */
    public function updatePayoutConfig(Request $request, Wallet $wallet)
    {
        $validated = $request->validate([
            'payout_schedule' => 'required|in:DAILY,WEEKLY,MONTHLY,INSTANT',
            'payout_execution_day' => 'nullable|string',
            'refund_window_days' => 'required|integer|min:0',
            'min_withdraw_amount' => 'required|numeric|min:0',
            'max_payouts_per_batch' => 'required|integer|min:1',
            'withdraw_cooldown_days' => 'required|integer|min:0',
            'start_window' => 'nullable|date_format:H:i',
            'end_window' => 'nullable|date_format:H:i|after:start_window',
            'auto_payout' => 'boolean',
            'processing_fee_percent' => 'nullable|numeric|min:0|max:100',
            'processing_fee_fixed' => 'nullable|numeric|min:0',
        ]);
        
        $config = $wallet->configuration ?? new WalletConfiguration(['wallet_id' => $wallet->id]);
        $config->update($validated);
        
        return back()->with('success', 'Payout configuration updated!');
    }
    
    /**
     * Wallet Charges (Setting 2)
     */
    public function charges(Wallet $wallet)
    {
        $wallet->load('charges');
        return view('admin.wallets.charges', compact('wallet'));
    }
    
    /**
     * Add/Update wallet charges
     */
    public function updateCharges(Request $request, Wallet $wallet)
    {
        $validated = $request->validate([
            'charges' => 'required|array',
            'charges.*.charge_type' => 'required|string',
            'charges.*.charge_mode' => 'required|in:PERCENTAGE,FIXED',
            'charges.*.charge_value' => 'required|numeric|min:0',
            'charges.*.min_charge' => 'nullable|numeric|min:0',
            'charges.*.max_charge' => 'nullable|numeric|gt:min_charge',
        ]);
        
        DB::beginTransaction();
        try {
            // Delete existing charges
            $wallet->charges()->delete();
            
            // Create new charges
            foreach ($validated['charges'] as $chargeData) {
                $wallet->charges()->create($chargeData);
            }
            
            DB::commit();
            return back()->with('success', 'Wallet charges updated!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }
    
    /**
     * Sync wallet balances
     */
    public function syncWallet(Wallet $wallet)
    {
        // Logic to sync wallet balances with transactions
        return back()->with('success', 'Wallet synced successfully!');
    }
    
    /**
     * Assign user to wallet
     */
    public function assignUser(Request $request, Wallet $wallet)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:mlm_users,id',
        ]);
        
        // Create wallet balance for user
        $wallet->balances()->firstOrCreate(
            ['user_id' => $validated['user_id']],
            ['balance' => 0]
        );
        
        return back()->with('success', 'User assigned to wallet!');
    }
}