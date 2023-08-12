<?php

namespace App\Http\Controllers\Web\User;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use App\Models\Carts;
use App\Models\Products;
use Carbon\Carbon;
use Auth;
use App\Http\Requests\Services\ValidateProductId;
use App\Http\Requests\Services\ValidateProductIdAdnQty;

class CartUserController extends BaseController
{
    public function addToCart(ValidateProductId $request)
    {
        $product = Products::where('id',$request->product_id)->first();
        $cartProduct = Carts::where('product_id',$request->product_id)->first();
        $randomNumberForOption = rand(config('app.rand_min'),config('app.rand_max'));
           
        if ($cartProduct !== null) {
            Carts::where('product_id',$request->product_id)->update([
                'totalQty'=>$cartProduct->totalQty +1,
            ]);
        } else {
            Carts::insertGetId([
                'random_number' => $randomNumberForOption,
                'sessionStartDate' => Carbon::now(config('app.timezone_now'))->toDateTimeString(),
                'sessionEndDate' => Carbon::now(config('app.timezone_now'))->addWeeks(1)->toDateTimeString(),
                'totalQty' => $request->totalQty ?? 1,
                'product_id' => $product->id,
                'user_id' => Auth::id(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        return view('user.cart',$this->getCart(Auth::id()));
    }

    public function showCart(Request $request)
    {
        return view('user.cart',$this->getCart(Auth::id()));
    }

    public function AddQuantityForOneProduct(ValidateProductIdAdnQty $request)
    {
        Carts::where('user_id', Auth::id())->where('product_id', $request->product_id)->update([
            'totalQty' => $request->qty
        ]);

        return view('user.cart',$this->getCart(Auth::id()));
    }

    public function deleteCartProducts(ValidateProductId $request)
    {
        Carts::where('user_id',Auth::id())->where('product_id',$request->product_id)->delete();

        return view('user.cart',$this->getCart(Auth::id()));
    }

    public function showChekoutPage(Request $request)
    {   
        return view('user.checkout',$this->getCart(Auth::id()));
    }

    protected function getCart($userID)
    {
        $cart = Carts::where('user_id', $userID)->with('product')->get()->groupBy('random_number');
        $randPrices = [];
        $product = []; 

        foreach ($cart as $items) {
            $productTable = Products::with('productPrice')->where('id', $items[0]->product_id)->first();
            $prod_price = $productTable->productPrice[0]->price * $items[0]->totalQty;
            $randPrice = 0;
            $randPrices[$items[0]->product_id] = $randPrice + $prod_price;
            $product[$items[0]->product_id] = $items[0];
        }
        $singlePrice = $randPrices;
        $sum = array_sum($randPrices);

        return compact('cart','singlePrice','sum');
    }
}
