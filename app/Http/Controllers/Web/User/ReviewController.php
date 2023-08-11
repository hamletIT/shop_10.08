<?php

namespace App\Http\Controllers\Web\User;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use App\Models\Products;
use App\Models\ReviewProducts;
use Auth;


class ReviewController extends BaseController
{
    public function addReviewToProduct($id)
    {
        return view('user.setreview',compact('id'));
    }
    
    public function saveReviewToProduct(Request $request)
    {
        $allProducts = Products::with('productImages','productPrice','reviewProducts','ratingProducts')->orderBy('created_at','desc')->paginate(10);
        ReviewProducts::create([
            'user_id' => Auth::id(),
            'product_id' => $request['product_id'],
            'text' => $request['text'],
        ]);

        return view('user.dashboard',compact('allProducts'));
    }
    
}
