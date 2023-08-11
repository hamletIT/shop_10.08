<?php

namespace App\Http\Controllers\Web\User;

use App\Models\RatingProducts;
use App\Models\Products;
use Illuminate\Http\Request;
use Auth;
use Illuminate\Routing\Controller as BaseController;

class RatingController extends BaseController
{
    public function addRatingToProduct($id)
    {
        return view('user.setrating',compact('id'));
    }

    public function saveRatingToProduct(Request $request)
    {
        $allProducts = Products::with('productImages','productPrice','reviewProducts','ratingProducts')->orderBy('created_at','desc')->paginate(10);
        RatingProducts::create([
            'user_id' => Auth::id(),
            'product_id' => $request['product_id'],
            'rating' => $request['rating'],
        ]);

        return view('user.dashboard',compact('allProducts'));
    }
}
