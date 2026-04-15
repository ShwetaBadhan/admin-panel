<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class MLMTree extends Model
{
    protected $table = 'mlm_trees';
    
    protected $fillable = [
        'mlm_user_id', 'parent_id', 'position', 'level',
        'package_id', 'business_volume', 'earned_amount', 'rank', 'registered_at'
    ];

    protected $casts = [
        'business_volume' => 'decimal:6',
        'earned_amount' => 'decimal:6',
        'level' => 'integer',
        'registered_at' => 'datetime',
    ];

    // 🔗 MLM User
    public function mlmUser(): BelongsTo
    {
        return $this->belongsTo(MlmUser::class, 'mlm_user_id');
    }

    // 🔗 Parent Node in Tree (self-referential)
    public function parent(): BelongsTo
    {
        return $this->belongsTo(MLMTree::class, 'parent_id');
    }

    // 🔗 Children Nodes
    public function children(): HasMany
    {
        return $this->hasMany(MLMTree::class, 'parent_id');
    }

    // 🔗 Left Child
    public function leftChild(): HasOne
    {
        return $this->hasOne(MLMTree::class, 'parent_id')->where('position', 'left');
    }

    // 🔗 Right Child
    public function rightChild(): HasOne
    {
        return $this->hasOne(MLMTree::class, 'parent_id')->where('position', 'right');
    }

    // 🔗 Package
    public function package(): BelongsTo
    {
        return $this->belongsTo(Package::class);
    }

    // 🔗 Closure: All Ancestors (via mlm_tree_closures)
    public function ancestors()
    {
        return $this->belongsToMany(
            MLMTree::class,
            'mlm_tree_closures',
            'descendant_id',
            'ancestor_id'
        )->withPivot('depth');
    }

    // 🔗 Closure: All Descendants
    public function descendants()
    {
        return $this->belongsToMany(
            MLMTree::class,
            'mlm_tree_closures',
            'ancestor_id',
            'descendant_id'
        )->withPivot('depth');
    }

    // ✅ Helper: Is this the root node?
    public function isRoot(): bool
    {
        return is_null($this->parent_id) && $this->position === 'none';
    }
}
