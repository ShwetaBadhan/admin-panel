<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CommunityWork extends Model
{
    //
    protected $fillable = [
        'sub_title',
        'main_title',
        'small_card_1_title',
        'small_card_1_main_title',
        'small_card_2_title',
        'small_card_2_main_title',
        'small_card_3_title',
        'small_card_3_main_title',
        'small_card_4_title',
        'small_card_4_main_title',
        'is_active',
    ];
}
