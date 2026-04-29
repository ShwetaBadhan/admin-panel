<?php

namespace App\Http\Controllers\MLM; 
use App\Http\Controllers\Controller;
use App\Models\MlmUser;
use App\Models\MLMTree;
use Illuminate\Http\Request;

class TeamGenealogyController extends Controller
{
    public function index()
    {
        // Get root user (Founder01 or first user)
        $rootUser = MlmUser::where('user_name', 'Founder01')->first();
        
        if (!$rootUser) {
            $rootUser = MlmUser::first();
        }
        
        $rootTree = MLMTree::where('mlm_user_id', $rootUser->id)
            ->with(['leftChild.mlmUser', 'rightChild.mlmUser'])
            ->first();
        
        // Build tree structure
        $treeData = $this->buildTreeStructure($rootTree);
        
        return view('admin.pages.mlm.team-genealogy', compact('treeData', 'rootUser'));
    }
    
    /**
     * Get user profile stats
     */
    public function userProfile($userId)
    {
        $user = MlmUser::with('sponsor')->findOrFail($userId);
        $tree = MLMTree::where('mlm_user_id', $userId)->first();
        
        return response()->json([
            'user' => $user,
            'tree' => $tree,
            'stats' => [
                'joined_date' => $user->created_at->format('d M Y H:i'),
                'level' => 'L-' . ($tree->level ?? 0),
                'sponsor' => $user->sponsor ? $user->sponsor->user_name : 'Direct Seller',
            ]
        ]);
    }
    
    /**
     * Build recursive tree structure
     */
    private function buildTreeStructure($treeNode, $depth = 0)
    {
        if (!$treeNode || $depth > 10) {
            return null;
        }
        
        $user = $treeNode->mlmUser;
        
        // Load children
        $leftChild = $treeNode->leftChild;
        $rightChild = $treeNode->rightChild;
        
        return [
            'id' => $treeNode->id,
            'user_id' => $user->id,
            'user_name' => $user->user_name,
            'first_name' => $user->first_name,
            'last_name' => $user->last_name,
            'email' => $user->email,
            'position' => $treeNode->position,
            'level' => $treeNode->level,
            'is_active' => $user->is_active,
            'is_root' => $depth === 0,
            'left' => $leftChild ? $this->buildTreeStructure($leftChild, $depth + 1) : null,
            'right' => $rightChild ? $this->buildTreeStructure($rightChild, $depth + 1) : null,
        ];
    }
}