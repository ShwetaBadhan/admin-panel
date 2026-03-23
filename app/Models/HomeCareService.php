<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HomeCareService extends Model
{
    //
    protected $fillable = [
        'sub_title',
        'main_title',
        'small_card_1_image',
        'small_card_1_title',
        'small_card_1_main_title',
        'small_card_2_image',
        'small_card_2_title',
        'small_card_2_main_title',
        'small_card_3_image',
        'small_card_3_title',
        'small_card_3_main_title',
        'is_active',
    ];
}
