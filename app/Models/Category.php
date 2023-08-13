<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Products;

class Category extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $fillable = [
        'title',
    ];

    public function products() 
    {
        return $this->belongsToMany(Products::class, 'pivot_categories_products', 'category_id', 'product_id');
    }
}
