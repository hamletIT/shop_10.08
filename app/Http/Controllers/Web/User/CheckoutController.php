<?php

namespace App\Http\Controllers\Web\User;

use App\Models\Orders;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use App\Models\Carts;
use App\Models\User;
use App\Models\Addresses;
use Auth;
use Intervention\Image\Exception\NotFoundException;
use Stripe\Checkout\Session;
use Stripe\Stripe;
use App\Http\Requests\Services\ValidateSessionID;

class CheckoutController extends BaseController
{
    public function checkout(Request $request)
    {
        Stripe::setApiKey(env('SRIPE_SECRET_KEY'));
        $lineItems = [];
        $randomNumber = rand(config('app.rand_min'), config('app.rand_max'));
        $cartProd = Carts::where('user_id',Auth::id())->with('product','product.productPrice')->get();
        $totalPrice = 0;
        $price = 0;

        foreach ($cartProd as $value) {
            $totalPrice += $value->product->productPrice[0]->price;
            $lineItems[] = [
                'price_data' => [
                  'currency' => 'usd',
                  'product_data' => [
                    'name' => $value->product->title,
                  ],
                  'unit_amount' => $value->product->productPrice[0]->price * 100,
                ],
                'quantity' => $value->totalQty,
            ];
            $price += $value->product->productPrice[0]->price * $value->totalQty;
        }
       
        if (empty($lineItems)) {
            throw new NotFoundException;
        }
        $session = Session::create([
            'line_items' => $lineItems,
              'mode' => 'payment',
              'success_url' => route('checkout.success', [], true)."?session_id={CHECKOUT_SESSION_ID}",
              'cancel_url' => route('checkout.cancel', [], true),
        ]);

        $userPhone= User::where('id',Auth::id())->first();
        $address = new Addresses();
        $address->user_id = Auth::id();
        $address->house_number = $request['house_number'];
        $address->street_name =$request['street_name'];
        $address->city = $request['city'];
        $address->phone = $userPhone->phone;
        $address->saved_address_status = '0';
        $address->status = 'unpaid';
        $address->save();

        $order = new Orders();
        $order->status = 'unpaid';
        $order->total_price = $price;
        $order->session_id = $session->id;
        $order->order_number = $randomNumber;
        $order->user_id = Auth::id();
        $order->address_id = $address->id;
        $order->save();

        $checkoutUrl = $session->url;

        return redirect($checkoutUrl);
    }

    public function successCheckout(Request $request)
    {
        $orders = Orders::paginate(10);

        Stripe::setApiKey(env('SRIPE_SECRET_KEY'));
        $cuctomer = null;
        $sessionId = $request->get('session_id');
        $validatedData = $this->validate($request, [
            'session_id' => [new ValidateSessionID],
        ]);
        $session = Session::retrieve($sessionId);
        
        if (!$session) {
            throw new NotFoundException;
            
        }
        $cuctomer = \Stripe\Customer::retrieve($session->customer);

        $order = Orders::where('session_id',$session->id)->where('status','unpaid')->get();
        if (!$order) {
            throw new NotFoundException;
        }
        Orders::where('session_id',$session->id)->where('status','unpaid')->update([
            'status' => 'paid'
        ]);
        Carts::where('user_id',Auth::id())->delete();
        Addresses::where('user_id',Auth::id())->where('status','unpaid')->update([
            'status' => 'paid'
        ]);
        
        return view('user.orders',compact('orders'));
       
    }

    public function cancelCheckout(Request $request)
    { 
        throw new NotFoundException;
    }

    public function showOrders(Request $request)
    { 
        $orders = Orders::with('address_order')->paginate(10);

        return view('user.orders',compact('orders'));
    }
}