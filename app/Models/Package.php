<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
     //

     protected $fillable = [
          'title',
          'slug',
          'status',
          'image',
          'description',
          'subparameter_id',
          

     ];
}
