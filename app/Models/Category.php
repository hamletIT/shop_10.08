<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\SubCategory; 
use App\Models\Products;
use App\Models\CategoryPhotos;

class Category extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $fillable = [
        'title',
    ];

    public function subCategory() 
    {
        return $this->belongsToMany(SubCategory::class, 'pivot_sub_categories_products', 'category_id', 'sub_category_id');
    }

    public function products() 
    {
        return $this->belongsToMany(Products::class, 'pivot_categories_products', 'category_id', 'product_id');
    }

    public function categoryImages()
    {
        return $this->hasMany(CategoryPhotos::class,'category_id');
    }
    
}
