<?php

namespace App\Http\Controllers\Web\User;

use App\Models\RatingProducts;
use Auth;
use App\Services\UserData;
use App\Http\Requests\Services\ValidateProductIdRating;
use Illuminate\Routing\Controller as BaseController;

class RatingController extends BaseController
{
    protected $userData;

    public function __construct(UserData $userData)
    {
        $this->userData = $userData;
    }
    public function addRatingToProduct($id)
    {
        return view('user.setrating',compact('id'));
    }

    public function saveRatingToProduct(ValidateProductIdRating $request)
    {
        RatingProducts::create([
            'user_id' => Auth::id(),
            'product_id' => $request['product_id'],
            'rating' => $request['rating'],
        ]);

        return $this->userData->dashboard();
    }
}
