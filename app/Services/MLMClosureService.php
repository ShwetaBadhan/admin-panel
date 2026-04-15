<?php

namespace App\Services;

use App\Models\MLMTree;
use App\Models\MLMTreeClosure;
use Illuminate\Support\Facades\DB;

class MLMClosureService
{
    public function syncClosures(MLMTree $newNode): void
    {
        DB::transaction(function () use ($newNode) {
            // Self reference
            MLMTreeClosure::updateOrCreate(
                ['ancestor_id' => $newNode->id, 'descendant_id' => $newNode->id],
                ['depth' => 0]
            );

            // Inherit parent's ancestors
            if ($newNode->parent) {
                $parentAncestors = MLMTreeClosure::where('descendant_id', $newNode->parent_id)->get();
                foreach ($parentAncestors as $ancestor) {
                    MLMTreeClosure::updateOrCreate(
                        [
                            'ancestor_id' => $ancestor->ancestor_id,
                            'descendant_id' => $newNode->id
                        ],
                        ['depth' => $ancestor->depth + 1]
                    );
                }
            }
        });
    }
}