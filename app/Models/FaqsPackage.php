<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FaqsPackage extends Model
{
    protected $table = 'package_faqs';

    protected $fillable = [
        'question',
        'answer',
        'parameter_id',
        'subparameter_id',
        'sort_order',
        'status'
    ];

    // Relationships
    public function parameter()
    {
        return $this->belongsTo(Parameter::class);
    }

    public function subparameter()
    {
        return $this->belongsTo(Subparameter::class);
    }

    // Helper to show linked title
    public function getLinkedTitleAttribute()
    {
        if ($this->parameter) {
            return 'Parameter: ' . $this->parameter->title;
        }
        if ($this->subparameter) {
            return 'Health Package: ' . $this->subparameter->title;
        }
        return 'General FAQ';
    }
}