<?php

namespace App\Http\Controllers\Web\User;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Validator;
use App\Models\Carts;
use App\Models\Products;
use App\Models\User;
use Carbon\Carbon;
use Auth;

class CartUserController extends BaseController
{
    public function addToCart(Request $request)
    {
        $rules = [
            'product_id' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
        }

        $product = Products::where('id',$request->product_id)->first();
        $cartProductNumber = Carts::where('product_id',$request->product_id)->first();
        $randomNumberForOption = rand(config('app.rand_min'),config('app.rand_max'));
           
        if ($cartProductNumber !== null) {
            Carts::where('product_id',$request->product_id)->update([
                'totalQty'=>$cartProductNumber->totalQty +1,
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
    public function AddQuantityForOneProduct(Request $request)
    {
        $rules = [
            'product_id' => 'required|numeric|integer',
            'qty' => 'required|numeric|integer',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
        }

        Carts::where('user_id', Auth::id())->where('product_id', $request->product_id)->update([
            'totalQty' => $request->qty
        ]);

        return view('user.cart',$this->getCart(Auth::id()));
    }

    public function deleteCartProducts(Request $request)
    {
        $rules = [
            'product_id' => 'required|numeric|integer',
        ];
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
        }
        
        Carts::where('user_id',Auth::id())->where('product_id',$request->product_id)->delete();

        return view('user.cart',$this->getCart(Auth::id()));
    }

    protected function getCart($userID)
    {
        $cart = Carts::where('user_id', $userID)->with('product')->get()->groupBy('random_number');
        $randPrices = [];
        $product = []; // Initialize the $product array here

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

    public function showChekoutPage(Request $request)
    {   
        return view('user.checkout',$this->getCart(Auth::id()));
    }
    
}
