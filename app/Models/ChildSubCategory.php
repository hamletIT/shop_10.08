<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\ChildSubCategoryPhotos;
use App\Models\Products;

class ChildSubCategory extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $fillable = [];

    public function ChildsubCategoryImages()
    {
        return $this->hasMany(ChildSubCategoryPhotos::class,'child_sub_category_id');
    }

    public function products() 
    {
        return $this->belongsToMany(Products::class, 'pivot_child_sub_categories', 'child_sub_category_id', 'product_id');
    }
    
}
