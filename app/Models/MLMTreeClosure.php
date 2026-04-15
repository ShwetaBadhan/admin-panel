<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
class MLMTreeClosure extends Model
{
    protected $table = 'mlm_tree_closures';
    
    public $incrementing = false;
    protected $primaryKey = ['ancestor_id', 'descendant_id'];
    protected $keyType = 'array'; // For composite key handling
    
    protected $fillable = ['ancestor_id', 'descendant_id', 'depth'];
    
    public $timestamps = false;

    // 🔗 Ancestor Node
    public function ancestor(): BelongsTo
    {
        return $this->belongsTo(MLMTree::class, 'ancestor_id');
    }

    // 🔗 Descendant Node
    public function descendant(): BelongsTo
    {
        return $this->belongsTo(MLMTree::class, 'descendant_id');
    }
}
