<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use App\Models\User;
use App\Models\Carts;
use App\Models\Products;
use App\Models\Stores;
use App\Models\Orders;
use App\Models\Options;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Jetstream\Jetstream;
use GuzzleHttp\Psr7\Request as Req;
use GuzzleHttp\Client;
use Carbon\Carbon;
use Illuminate\Support\Facades\File;
use App\Http\Services\ApiVarableServices;
use Illuminate\Routing\Controller as BaseController;

class ApiOrderController extends BaseController
{
    public function __construct(
        public ApiVarableServices $apiVarableServices,
    ) {
       
    }
    /**
    * @OA\Post(
    *     path="/api/create/order",
    *     summary="The request that creates the order",
    *     description="",
    *     tags={"Order Section"},
    *     @OA\Parameter(
    *        name="user_id",
    *        in="query",
    *        description="Please write user ID",
    *        required=true,
    *        allowEmptyValue=true,
    *     ),
    *     @OA\Response(
    *        response=200,
    *        description="OK",
    *        @OA\MediaType(
    *            mediaType="application/json",
    *        )
    *     ),
    *     @OA\Response(
    *         response=401,
    *         description="Unauthenticated",
    *     ),
    *     @OA\Response(
    *         response=403,
    *         description="Forbidden"
    *     ),
    *     @OA\Response(
    *         response=429,
    *         description="validation error"
    *     )
    *   ),
    * )
    */
    public function createOrder(Request $request)
    {
        $rules = [
            'user_id' => 'required',
        ];
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
        }
        $user = User::find($request['user_id']);
        $userCart = $this->apiVarableServices->getCart($request['user_id']);

        // dd($userCart);
        if (isset($userCart['products']) && isset($userCart['product_prices']) && is_array($userCart['products']) && is_array($userCart['product_prices'])) {
            foreach ($userCart['products'] as $cart) {
                if (isset($cart['product_id'])) {
                    $randomNumber = rand(config('app.rand_min'), config('app.rand_max'));
                    
                    // Get the product price based on the product_id
                    $productPrice = isset($userCart['product_prices']['product_id: ' . $cart['product_id']]) ? $userCart['product_prices']['product_id: ' . $cart['product_id']] : 0;
                    Orders::insert([
                        'user_id' => $request['user_id'],
                        'product_id' => $cart['product_id'],
                        'location' => $request['location'] !== null ? $request['location'] : '',
                        'totalQty' => $cart['totalQty'] !== null ? $cart['totalQty'] : null,
                        'totalPrice' => $productPrice, // Assign the calculated product price
                        'order_number' => $randomNumber !== null ? $randomNumber : null,
                        'status' => 1,
                        'order_note' => $request['order_note'] !== null ? $request['order_note'] : null,
                        'customer_name' => $user->name,
                        'customer_email' => $user->email,
                        'customer_phone' => $user->phone,
                        'updated_at' => now(),
                        'created_at' => now(),
                    ]);
                }
            }

        }

        return response()->json($this->apiVarableServices->getOrders());
    }

    /**
    * @OA\Get(
    *     path="/api/get/all/orders",
    *     summary="Request which returns orders",
    *     description="",
    *     tags={"Order Section"},
    *     @OA\Response(
    *        response=200,
    *        description="OK",
    *        @OA\MediaType(
    *            mediaType="application/json",
    *        )
    *     ),
    *     @OA\Response(
    *         response=401,
    *         description="Unauthenticated",
    *     ),
    *     @OA\Response(
    *         response=403,
    *         description="Forbidden"
    *     ),
    *     @OA\Response(
    *         response=429,
    *         description="validation error"
    *     )
    *   ),
    * )
    */
    public function getAllOrders(Request $request)
    {
        return response()->json($this->apiVarableServices->getOrders());
    }

    /**
    * @OA\Get(
    *     path="/api/get/order",
    *     summary="Request which returns certein order",
    *     description="",
    *     tags={"Order Section"},
    *     @OA\Parameter(
    *        name="user_id",
    *        in="query",
    *        description="Please write order ID",
    *        required=true,
    *        allowEmptyValue=true,
    *     ),
    *     @OA\Response(
    *        response=200,
    *        description="OK",
    *        @OA\MediaType(
    *            mediaType="application/json",
    *        )
    *     ),
    *     @OA\Response(
    *         response=401,
    *         description="Unauthenticated",
    *     ),
    *     @OA\Response(
    *         response=403,
    *         description="Forbidden"
    *     ),
    *     @OA\Response(
    *         response=429,
    *         description="validation error"
    *     )
    *   ),
    * )
    */
    public function getSingleOrder(Request $request)
    {
        $rules = [
            'user_id' => 'required',
        ];
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
        }
        
        return response()->json($this->apiVarableServices->getOrder($request->user_id));
    }

    /**
    * @OA\Post(
    *     path="/api/delete/order",
    *     summary="Request which deletes order",
    *     description="",
    *     tags={"Order Section"},
    *     @OA\Parameter(
    *        name="user_id",
    *        in="query",
    *        description="Please write User ID",
    *        required=true,
    *        allowEmptyValue=true,
    *     ),
    *     @OA\Response(
    *        response=200,
    *        description="OK",
    *        @OA\MediaType(
    *            mediaType="application/json",
    *        )
    *     ),
    *     @OA\Response(
    *         response=401,
    *         description="Unauthenticated",
    *     ),
    *     @OA\Response(
    *         response=403,
    *         description="Forbidden"
    *     ),
    *     @OA\Response(
    *         response=429,
    *         description="validation error"
    *     )
    *   ),
    * )
    */
    public function deleteOrder(Request $request)
    {
        $rules = [
            'user_id' => 'required',
        ];
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
        }
        
        $Orders = Orders::where('user_id',$request->user_id)->update([
            'status' => 0,
        ]);
        if($Orders) {
            return response()->json(['status' =>true,'order'=>'found and removed.']);
        }

        return response()->json(['status' =>false,'order'=>'not found']);
    }
}