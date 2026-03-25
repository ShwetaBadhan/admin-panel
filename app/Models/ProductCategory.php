<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductCategory extends Model
{
    protected $fillable = ['name', 'slug', 'image', 'status'];

    // Optional: Status ko string mein convert karne ke liye
    public function getStatusLabelAttribute()
    {
        return $this->status == 1 ? 'Active' : 'Inactive';
    }

    public function getStatusBadgeAttribute()
    {
        return $this->status == 1 ? 'success' : 'danger';
    }
}