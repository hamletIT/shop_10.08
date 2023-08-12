<?php

namespace App\Http\Controllers\Web\User;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use App\Models\Products;
use App\Models\ReviewProducts;
use Auth;
use App\Services\UserData;
use App\Http\Requests\Services\ValidateProductIdReview;

class ReviewController extends BaseController
{
    protected $userData;

    public function __construct(UserData $userData)
    {
        $this->userData = $userData;
    }
    public function addReviewToProduct($id)
    {
        return view('user.setreview',compact('id'));
    }
    
    public function saveReviewToProduct(ValidateProductIdReview $request)
    {
        ReviewProducts::create([
            'user_id' => Auth::id(),
            'product_id' => $request['product_id'],
            'text' => $request['text'],
        ]);

        return $this->userData->dashboard();
    }
    
}
