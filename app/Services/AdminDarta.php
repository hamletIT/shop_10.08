<?php

namespace App\Services;

use App\Models\Products;
use App\Models\Prices;
use App\Models\Category;

class AdminDarta
{
    public function dashboard()
    {
        $allProducts = Products::with('productImages','productPrice')->orderBy('created_at','desc')->paginate(10);
        $collection = collect(Prices::pluck('price'));
        $maxValue = $collection->max();
        $minValue = $collection->min();
        $allCategory = Category::get();

        return redirect()->route('admin.dashboard',compact('allProducts','maxValue','minValue','allCategory'));
    }
}