<?php

namespace App\Http\Controllers\Web\User;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\BigStores;
use App\Models\User;
use App\Models\Prices;
use App\Models\Products;
use App\Models\Applications;
use App\Models\pivot_categories_products;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use App\Http\Services\VarableServices;
use Illuminate\Support\Facades\Hash;
use Illuminate\Routing\Controller as BaseController;
use Auth;
use DB;
use App\Http\Requests\Services\ValidateProductFilter;


class ProductController extends BaseController
{

    public function filterProduct(ValidateProductFilter $request)
    {
        $query = Products::query();
        $collection = collect(Prices::pluck('price'));
        $maxValue = $request->max_val;
        $minValue = $request->min_val;
        $allCategory = Category::get();
    
        if ($request->has('title') && $request->title !== null) {
            $query->where('title', 'LIKE', '%' . $request->input('title') . '%');
        }
    
        if ($request->has('category_id') && $request->category_id !== null) {
            $pivotTable = pivot_categories_products::where('category_id', $request->input('category_id'))->get();
            $categoryIds = $pivotTable->pluck('product_id');
            $query->whereIn('id', $categoryIds);
        }
    
        if ($request->has('min_val') && $request->has('max_val')) {
            $min = $request->input('min_val');
            $max = $request->input('max_val');
            
            $query->whereHas('productPrice', function ($q) use ($min, $max) {
                $q->where('price', '>', $min)->where('price', '<', $max);
            });
        }
    
        $allProducts = $query->with('productImages','productPrice')->orderBy('created_at','desc')->paginate(10);
    
        return view('user.dashboard',compact('allProducts','maxValue','minValue','allCategory'));
    }
}