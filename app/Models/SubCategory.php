<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\ChildSubCategory;
use App\Models\SubCategoryPhotos;
use App\Models\Products;

class SubCategory extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $fillable = [
        'title',
    ];

    public function categories()
    {
        return $this->belongsToMany(ChildSubCategory::class, 'pivot_child_sub_categories', 'child_sub_category_id', 'sub_category_id');
    }

    public function subCategoryImages()
    {
        return $this->hasMany(SubCategoryPhotos::class,'sub_category_id');
    }

    public function products() 
    {
        return $this->belongsToMany(Products::class, 'pivot_sub_categories_products', 'sub_category_id', 'product_id');
    }
    
}
