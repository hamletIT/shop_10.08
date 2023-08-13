<?php

namespace App\Http\Controllers\Web\User;

use App\Models\ReviewProducts;
use Auth;
use App\Services\UserData;
use App\Http\Requests\Services\ValidateProductIdReview;
use Illuminate\Routing\Controller as BaseController;

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
