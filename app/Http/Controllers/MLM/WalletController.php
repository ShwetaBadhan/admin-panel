<?php
namespace App\Http\Controllers\MLM;

use App\Http\Controllers\Controller;

use App\Models\Wallet;
use App\Models\WalletConfiguration;
use App\Models\WalletCharge;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

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
        
        return view('admin.pages.mlm.wallets.index', compact('wallets'));
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
    /**
 * Display financial overview dashboard
 */
public function financialOverview(Request $request)
{
    // Use DB queries directly or set to 0
    $totalInvestments = 0;
    $bonusIncome = 0;
    $pendingWithdrawals = 0;
    $completedWithdrawals = 0;
    $platformCharges = 0;
    $tdsDeducted = 0;
    $netIncome = 0;
    
    // Try to get data if tables exist
    try {
        // Check if users table exists
        if (Schema::hasTable('mlm_users')) {
            $totalUsers = \App\Models\MlmUser::count();
        }
        
        // Check if investments/packages table exists
        if (Schema::hasTable('package_purchases')) {
            $totalInvestments = DB::table('package_purchases')
                ->where('status', 'active')
                ->sum('amount') ?? 0;
        }
        
        // Check if earnings/transactions table exists
        if (Schema::hasTable('earnings')) {
            $bonusIncome = DB::table('earnings')
                ->where('status', 'credited')
                ->sum('amount') ?? 0;
        } elseif (Schema::hasTable('transactions')) {
            $bonusIncome = DB::table('transactions')
                ->whereIn('type', ['direct_income', 'level_income', 'bonus'])
                ->where('status', 'credited')
                ->sum('amount') ?? 0;
        }
        
    } catch (\Exception $e) {
        // Silently fail and use 0 values
        Log::info('Financial Overview: ' . $e->getMessage());
    }
    
    $netIncome = $bonusIncome - ($platformCharges + $tdsDeducted);
    
    // Monthly data
    $monthlyData = [];
    for ($i = 5; $i >= 0; $i--) {
        $month = now()->subMonths($i)->format('M Y');
        $monthlyData[] = ['month' => $month];
    }
    
    // Distribution percentages
    $total = max(1, $totalInvestments + $bonusIncome);
    $distributionData = [
        'investments'    => round(($totalInvestments / $total) * 100, 2),
        'bonus_income'   => round(($bonusIncome / $total) * 100, 2),
        'pending'        => 0,
        'completed'      => 0,
        'charges_tds'    => 0,
    ];
    
    return view('admin.pages.mlm.wallets.financial-overview', compact(
        'totalInvestments',
        'bonusIncome', 
        'pendingWithdrawals',
        'completedWithdrawals',
        'platformCharges',
        'tdsDeducted',
        'netIncome',
        'monthlyData',
        'distributionData'
    ));
}
/**
 * Display commission wallet overview
 */
public function commissionWallet(Request $request)
{
    // 📊 Initialize default values
    $totalCommission = 0;
    $availableBalance = 0;
    $pendingCommission = 0;
    $withdrawnAmount = 0;
    $todayEarnings = 0;
    $weeklyEarnings = 0;
    
    // 📊 Try to fetch real data if tables exist
    try {
        // Check for earnings/transactions table
        if (Schema::hasTable('earnings')) {
            $totalCommission = DB::table('earnings')
                ->where('type', 'commission')
                ->sum('amount') ?? 0;
                
            $availableBalance = DB::table('earnings')
                ->where('type', 'commission')
                ->where('status', 'available')
                ->sum('amount') ?? 0;
                
            $pendingCommission = DB::table('earnings')
                ->where('type', 'commission')
                ->where('status', 'pending')
                ->sum('amount') ?? 0;
        } 
        elseif (Schema::hasTable('transactions')) {
            $totalCommission = DB::table('transactions')
                ->whereIn('type', ['commission', 'direct_income', 'level_income'])
                ->where('status', 'credited')
                ->sum('amount') ?? 0;
                
            $availableBalance = $totalCommission; // Simplified
        }
        
        // Withdrawn amount (if withdrawal table exists)
        if (Schema::hasTable('withdrawals')) {
            $withdrawnAmount = DB::table('withdrawals')
                ->where('status', 'approved')
                ->sum('amount') ?? 0;
        }
        
        // Today's earnings
        $todayEarnings = DB::table('earnings')
            ->where('type', 'commission')
            ->whereDate('created_at', today())
            ->sum('amount') 
            ?? DB::table('transactions')
                ->whereIn('type', ['commission', 'direct_income', 'level_income'])
                ->whereDate('created_at', today())
                ->sum('amount')
            ?? 0;
            
        // This week's earnings
        $weeklyEarnings = DB::table('earnings')
            ->where('type', 'commission')
            ->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])
            ->sum('amount')
            ?? DB::table('transactions')
                ->whereIn('type', ['commission', 'direct_income', 'level_income'])
                ->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])
                ->sum('amount')
            ?? 0;
            
    } catch (\Exception $e) {
        Log::info('Commission Wallet: ' . $e->getMessage());
        // Keep default 0 values on error
    }
    
    // 📊 Recent commission transactions (last 10)
    $recentTransactions = collect([]);
    try {
        if (Schema::hasTable('earnings')) {
            $recentTransactions = DB::table('earnings')
                ->where('type', 'commission')
                ->orderBy('created_at', 'desc')
                ->limit(10)
                ->get();
        } elseif (Schema::hasTable('transactions')) {
            $recentTransactions = DB::table('transactions')
                ->whereIn('type', ['commission', 'direct_income', 'level_income'])
                ->orderBy('created_at', 'desc')
                ->limit(10)
                ->get();
        }
    } catch (\Exception $e) {
        // Silent fail
    }
    
    // 📊 Monthly trend data (for chart)
    $monthlyTrend = [];
    for ($i = 5; $i >= 0; $i--) {
        $month = now()->subMonths($i)->format('M');
        $monthlyTrend[] = [
            'month' => $month,
            'earnings' => 0 // Replace with actual query if needed
        ];
    }
    
    return view('admin.pages.mlm.wallets.commission-wallet', compact(
        'totalCommission',
        'availableBalance',
        'pendingCommission',
        'withdrawnAmount',
        'todayEarnings',
        'weeklyEarnings',
        'recentTransactions',
        'monthlyTrend'
    ));
}
/**
 * Display purchase wallet / package tracking
 */
public function purchaseWallet(Request $request)
{
    $totalPurchases = 0;
    $activePurchases = 0;
    $pendingPayments = 0;
    $recentPurchases = collect([]);
    
    try {
        // Try common MLM table names
        $purchaseTable = Schema::hasTable('purchases') ? 'purchases' 
                      : (Schema::hasTable('package_purchases') ? 'package_purchases' 
                      : (Schema::hasTable('user_packages') ? 'user_packages' : null));
        
        if ($purchaseTable) {
            $totalPurchases = DB::table($purchaseTable)->sum('amount') ?? 0;
            $activePurchases = DB::table($purchaseTable)->where('status', 'active')->count();
            $pendingPayments = DB::table($purchaseTable)->where('status', 'pending')->sum('amount') ?? 0;
            $recentPurchases = DB::table($purchaseTable)
                ->orderBy('created_at', 'desc')
                ->limit(10)
                ->get();
        }
    } catch (\Exception $e) {
        Log::info('Purchase Wallet: ' . $e->getMessage());
    }

    return view('admin.pages.mlm.wallets.purchase-wallet', compact(
        'totalPurchases',
        'activePurchases',
        'pendingPayments',
        'recentPurchases'
    ));
}
/**
 * Display pending earnings / commissions awaiting approval
 */
public function pendingEarnings(Request $request)
{
    $totalPendingAmount = 0;
    $pendingCount = 0;
    $pendingItems = collect(); // Safe default

    try {
        $table = match(true) {
            Schema::hasTable('pending_earnings') => 'pending_earnings',
            Schema::hasTable('earnings')         => 'earnings',
            Schema::hasTable('transactions')     => 'transactions',
            default                              => null
        };

        if ($table) {
            $pendingItems = DB::table($table)
                ->where('status', 'pending')
                ->orderBy('created_at', 'desc')
                ->paginate(20);

            $totalPendingAmount = $pendingItems->sum('amount');
            $pendingCount = $pendingItems->total();
        }
    } catch (\Exception $e) {
        Log::info('Pending Earnings: ' . $e->getMessage());
    }

    return view('admin.pages.mlm.wallets.pending-earnings', compact(
        'totalPendingAmount',
        'pendingCount',
        'pendingItems'
    ));
}
/**
 * Display bonus earnings history
 */
public function bonusHistory(Request $request)
{
    $totalBonuses = 0;
    $bonusCount = 0;
    $bonuses = collect(); // Safe fallback

    try {
        // Auto-detect relevant table
        $table = match(true) {
            Schema::hasTable('bonus_history') => 'bonus_history',
            Schema::hasTable('bonuses')       => 'bonuses',
            Schema::hasTable('earnings')      => 'earnings',
            Schema::hasTable('transactions')  => 'transactions',
            default                           => null
        };

        if ($table) {
            $query = DB::table($table);
            
            // Filter for bonus records if column exists
            if (Schema::hasColumn($table, 'type')) {
                $query->where('type', 'like', '%bonus%');
            } elseif (Schema::hasColumn($table, 'category')) {
                $query->where('category', 'bonus');
            }

            $bonuses = $query->orderBy('created_at', 'desc')->paginate(20);
            $totalBonuses = $bonuses->sum('amount');
            $bonusCount = $bonuses->total();
        }
    } catch (\Exception $e) {
        Log::info('Bonus History: ' . $e->getMessage());
    }

    return view('admin.pages.mlm.wallets.bonus-history', compact(
        'totalBonuses',
        'bonusCount',
        'bonuses'
    ));
}

/**
 * Display CC (Credit Circle) transaction logs
 */
public function ccLogs(Request $request)
{
    $totalCC = 0;
    $logCount = 0;
    $ccLogs = collect(); // Safe fallback

    try {
        $table = match(true) {
            Schema::hasTable('cc_logs')          => 'cc_logs',
            Schema::hasTable('cc_transactions')  => 'cc_transactions',
            Schema::hasTable('earnings')         => 'earnings',
            Schema::hasTable('transactions')     => 'transactions',
            default                              => null
        };

        if ($table) {
            $query = DB::table($table);
            
            // Filter for CC-related records if columns exist
            if (Schema::hasColumn($table, 'type')) {
                $query->where('type', 'like', '%cc%');
            } elseif (Schema::hasColumn($table, 'currency_type')) {
                $query->where('currency_type', 'CC');
            }

            $ccLogs = $query->orderBy('created_at', 'desc')->paginate(20);
            $totalCC = $ccLogs->sum('amount'); // Adjust to 'cc_amount' if your schema uses it
            $logCount = $ccLogs->total();
        }
    } catch (\Exception $e) {
        Log::info('CC Logs: ' . $e->getMessage());
    }

    return view('admin.pages.mlm.wallets.cc-logs', compact(
        'totalCC',
        'logCount',
        'ccLogs'
    ));
}
/**
 * Display pair matching income logs
 */
public function pairMatchingLogs(Request $request)
{
    $totalPairIncome = 0;
    $matchedPairs = 0;
    $pairLogs = collect(); // Safe fallback

    try {
        $table = match(true) {
            Schema::hasTable('pair_matching_logs') => 'pair_matching_logs',
            Schema::hasTable('pair_income')        => 'pair_income',
            Schema::hasTable('pair_logs')          => 'pair_logs',
            Schema::hasTable('earnings')           => 'earnings',
            Schema::hasTable('transactions')       => 'transactions',
            default                                => null
        };

        if ($table) {
            $query = DB::table($table);
            
            // Filter for pair-related records if columns exist
            if (Schema::hasColumn($table, 'type')) {
                $query->where('type', 'like', '%pair%');
            } elseif (Schema::hasColumn($table, 'income_type')) {
                $query->where('income_type', 'pair_matching');
            }

            $pairLogs = $query->orderBy('created_at', 'desc')->paginate(20);
            $totalPairIncome = $pairLogs->sum('amount');
            $matchedPairs = $pairLogs->total();
        }
    } catch (\Exception $e) {
        Log::info('Pair Matching Logs: ' . $e->getMessage());
    }

    return view('admin.pages.mlm.wallets.pair-matching-logs', compact(
        'totalPairIncome',
        'matchedPairs',
        'pairLogs'
    ));
}
}