<?php
namespace App\Http\Controllers\MLM;

use App\Http\Controllers\Controller;
use App\Models\MlmUser;
use App\Models\MLMTree;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ReferralDownlineController extends Controller
{
    
    /**
     * Referral Downline - Table View
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        
        // Query builder for better performance
        $query = MlmUser::with(['sponsor', 'payoutBalance'])
            ->where('sponsor_id', $user->id)
            ->where('is_deleted', false);
        
        // 🔍 Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('user_name', 'LIKE', "%{$search}%")
                  ->orWhere('first_name', 'LIKE', "%{$search}%")
                  ->orWhere('last_name', 'LIKE', "%{$search}%")
                  ->orWhere('email', 'LIKE', "%{$search}%");
            });
        }
        
        // 📊 Filter functionality
        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }
        
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }
        
        // 📈 Sorting
        $sortBy = $request->get('sort', 'created_at');
        $sortOrder = $request->get('order', 'desc');
        $query->orderBy($sortBy, $sortOrder);
        
        $downlines = $query->paginate(20);
        
        // 📊 Stats
        $stats = [
            'total' => MlmUser::where('sponsor_id', $user->id)->where('is_deleted', false)->count(),
            'active' => MlmUser::where('sponsor_id', $user->id)->where('is_active', true)->where('is_deleted', false)->count(),
            'inactive' => MlmUser::where('sponsor_id', $user->id)->where('is_active', false)->where('is_deleted', false)->count(),
            'total_earned' => MlmUser::where('sponsor_id', $user->id)
                ->where('is_deleted', false)
                ->withSum('payoutBalance', 'total_earned')
                ->get()
                ->sum('payout_balance_sum_total_earned'),
        ];
        
        return view('admin.pages.mlm.referral-downline', compact('downlines', 'stats'));
    }
    
    /**
     * View User's Genealogy Tree
     */
    public function viewGenealogy($userId)
    {
        $user = MlmUser::findOrFail($userId);
        $treeData = $this->buildTreeStructure($user->id);
        
        return view('admin.pages.mlm.partials.genealogy-tree', compact('treeData', 'user'));
    }
    
    /**
     * View User's Referral Tree (Direct only)
     */
    public function viewReferralTree($userId)
    {
        $user = MlmUser::findOrFail($userId);
        $referrals = MlmUser::where('sponsor_id', $userId)
            ->where('is_deleted', false)
            ->with('payoutBalance')
            ->get();
        
        return view('admin.pages.mlm.partials.referral-tree', compact('referrals', 'user'));
    }
    
    /**
     * Build tree structure for a user
     */
    private function buildTreeStructure($userId, $depth = 0, $maxDepth = 5)
    {
        if ($depth > $maxDepth) return null;
        
        $user = MlmUser::with(['tree', 'payoutBalance'])->findOrFail($userId);
        $tree = $user->tree;
        
        // Get direct referrals
        $referrals = MlmUser::where('sponsor_id', $userId)
            ->where('is_deleted', false)
            ->with('payoutBalance')
            ->get();
        
        $children = [];
        foreach ($referrals as $ref) {
            $children[] = [
                'user' => $ref,
                'children' => $this->buildTreeStructure($ref->id, $depth + 1, $maxDepth),
            ];
        }
        
        return [
            'user' => $user,
            'level' => $tree?->level ?? 0,
            'position' => $tree?->position ?? 'none',
            'cc_balance' => $user->payoutBalance?->cc_balance ?? 0,
            'children' => $children,
        ];
    }
}