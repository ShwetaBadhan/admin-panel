<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AgedService extends Model
{
    //
    protected $fillable = [
        'sub_title',
        'main_title',
        'small_card_1_title',
        'small_card_1_content',
        'small_card_2_title',
        'small_card_2_content',
        'small_card_3_title',
        'small_card_3_content',
        'is_active',
    ];
}
