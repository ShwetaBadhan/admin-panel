<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClientResource extends Model
{
    //
    protected $fillable=[
        'title',
        'description',
        'image',
        'pdf',
        'status'
    ];
}
