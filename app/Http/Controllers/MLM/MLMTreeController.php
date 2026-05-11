<?php
namespace App\Http\Controllers\MLM;

use App\Http\Controllers\Controller;
use App\Models\MlmUser;
use App\Models\MLMTree;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MLMTreeController extends Controller
{
     /**
     * ✅ Referral Genealogy - Card View (Direct Referrals Only)
     */
    public function referralIndex()
    {
        $user = Auth::user();
        if (!$user) return redirect()->route('login');

        // Direct referrals (Level 1 only)
        $referrals = MlmUser::where('sponsor_id', $user->id)
            ->where('is_deleted', false)
            ->with('payoutBalance')
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        // Quick stats
        $stats = [
            'total' => MlmUser::where('sponsor_id', $user->id)->where('is_deleted', false)->count(),
            'active' => MlmUser::where('sponsor_id', $user->id)->where('is_active', true)->where('is_deleted', false)->count(),
            'total_cc' => MlmUser::where('sponsor_id', $user->id)
                ->where('is_deleted', false)
                ->withSum('payoutBalance', 'cc_balance')
                ->get()
                ->sum('payout_balance_sum_cc_balance'),
        ];

        return view('admin.pages.mlm.referral-genealogy', compact('referrals', 'stats', 'user'));
    }

    /**
     * ✅ User Profile for Modal (AJAX)
     */
    public function referralProfile($userId)
    {
        $user = MlmUser::with(['sponsor', 'payoutBalance'])->findOrFail($userId);
        
        return response()->json([
            'user' => $user,
            'stats' => [
                'joined' => $user->created_at->format('d M Y'),
                'status' => $user->is_active ? 'Active' : 'Inactive',
                'cc_balance' => $user->payoutBalance?->cc_balance ?? 0,
                'available' => $user->payoutBalance?->available_balance ?? 0,
                'sponsor' => $user->sponsor?->user_name ?? 'Direct',
            ]
        ]);
    }


public function holdingTank()
    {
        $holdingUsers = MLMTree::with(['mlmUser.sponsor'])
            ->whereHas('mlmUser', fn($q) => $q->where('is_verified', true)->where('is_active', true))
            ->whereNull('parent_id')->where('position', 'none')
            ->whereHas('mlmUser', fn($q) => $q->where('user_name', '!=', 'Founder01'))
            ->latest()->paginate(15);

        $parents = MlmUser::where('is_active', true)->where('is_deleted', false)
            ->orderBy('user_name')->get(['id', 'user_name', 'first_name', 'last_name']);

        return view('admin.pages.mlm.holding-tank', compact('holdingUsers', 'parents'));
    }

    public function placeUser(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:mlm_users,id',
            'parent_id' => 'required|exists:mlm_users,id',
            'position' => 'required|in:left,right',
        ]);

        if ($validated['user_id'] == $validated['parent_id']) {
            return back()->withErrors(['error' => 'Cannot place user under themselves.']);
        }

        DB::beginTransaction();
        try {
            $userTree = MLMTree::where('mlm_user_id', $validated['user_id'])
                ->where(function($q) { $q->whereNull('parent_id')->orWhere('position', 'none'); })
                ->with('mlmUser')->firstOrFail();
            
            $user = $userTree->mlmUser;
            if (!$user->is_verified || !$user->is_active) {
                throw new \Exception('User must be verified and active.');
            }

            $parentTree = MLMTree::where('mlm_user_id', $validated['parent_id'])->firstOrFail();
            
            if (MLMTree::where('parent_id', $parentTree->id)->where('position', $validated['position'])->exists()) {
                throw new \Exception("Position '{$validated['position']}' already occupied.");
            }

            $userTree->update([
                'parent_id' => $parentTree->id,
                'position' => $validated['position'],
                'level' => $parentTree->level + 1,
            ]);

            // Sync closure table
            app(\App\Services\MLMClosureService::class)->syncClosures($userTree, $validated['parent_id']);
            $user->update(['position_in_sponsor_leg' => $validated['position']]);

            DB::commit();
            return back()->with('success', "✅ User placed in {$validated['position']} leg!");
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }
}