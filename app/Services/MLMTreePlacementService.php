<?php

namespace App\Services;

use App\Models\MLMTree;
use App\Models\SpillingPreference;
use Illuminate\Support\Collection;

class MLMTreePlacementService
{
    public function findPlacement(?int $sponsorId, ?int $userId): ?array
    {
        if (is_null($sponsorId)) {
            return ['parent_id' => null, 'position' => 'none', 'level' => 0];
        }

        $sponsorTree = MLMTree::where('mlm_user_id', $sponsorId)->first();
        if (!$sponsorTree) {
            return ['parent_id' => null, 'position' => 'none', 'level' => 0];
        }

        if (!$sponsorTree->leftChild) {
            return ['parent_id' => $sponsorTree->id, 'position' => 'left', 'level' => $sponsorTree->level + 1];
        }
        if (!$sponsorTree->rightChild) {
            return ['parent_id' => $sponsorTree->id, 'position' => 'right', 'level' => $sponsorTree->level + 1];
        }

        $spillPref = SpillingPreference::where('mlm_user_id', $userId)->first();
        if ($spillPref && $spillPref->preference === 'HOLDING_TANK') {
            return null;
        }

        return $this->bfsFindSpot($sponsorTree, $spillPref?->preference);
    }

    private function bfsFindSpot(MLMTree $root, ?string $preference): ?array
    {
        $queue = collect([$root]);

        while ($queue->isNotEmpty()) {
            $node = $queue->shift();

            if ($preference !== 'RIGHT' && !$node->leftChild) {
                return ['parent_id' => $node->id, 'position' => 'left', 'level' => $node->level + 1];
            }
            if ($preference !== 'LEFT' && !$node->rightChild) {
                return ['parent_id' => $node->id, 'position' => 'right', 'level' => $node->level + 1];
            }

            if ($node->leftChild) $queue->push($node->leftChild);
            if ($node->rightChild) $queue->push($node->rightChild);
        }

        return null;
    }
}