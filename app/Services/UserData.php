<?php

namespace App\Services;

use App\Models\Products;
use App\Models\Prices;
use App\Models\Category;

class UserData
{
    public function dashboard()
    {
        $allProducts = Products::with('productImages','productPrice')->orderBy('created_at','desc')->paginate(10);
        $collection = collect(Prices::pluck('price'));
        $maxValue = $collection->max();
        $minValue = $collection->min();
        $allCategory = Category::get();

        return view('user.dashboard',compact('allProducts','maxValue','minValue','allCategory'));
    }
}