<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CommunityEligiblilityFaq extends Model
{

    protected $fillable = [
        'question',
        'answer',
        'status'
    ];
}
