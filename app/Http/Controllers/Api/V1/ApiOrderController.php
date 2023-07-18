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
use Illuminate\Routing\Controller as BaseController;

class ApiOrderController extends BaseController
{
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
    *     @OA\Parameter(
    *        name="store_id",
    *        in="query",
    *        description="Please write store ID",
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
            'store_id' => 'required',
        ];
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
        }
        $user = User::find($request['user_id']);
        $userCart = Carts::where('user_id',$request->user_id)->get();
        
        $ownOrder = Orders::where('user_id',$request->user_id)->get();
       
        if (count($ownOrder) == 0) {
            foreach ($userCart as $key => $value) {
                $randomNumber = rand(config('app.rand_min'),config('app.rand_max'));
                
                $order = Orders::insertGetID([
                    'product_id' => $value['product_id'] !== null ? $value['product_id'] : null,
                    'user_id' => $request['user_id'] !== null ? $request['user_id'] : null,
                    'store_id' => $request['store_id'] !== null ? $request['store_id'] : null,
                    'method' => $request['method']  !== null ? $request['method'] : null,
                    'location' => $request['location']  !== null ? $request['location'] :'',
                    'totalQty' => $value['totalQty'] !== NULL ? $value['totalQty'] : nulL,
                    'totalPrice' => $value['totalPrice'] !== null ? $value['totalPrice'] : null,
                    'order_number' => $randomNumber  !== null ? $randomNumber : null,
                    'payment_status' => null,
                    'status' => 0,
                    'order_note' => $request['order_note']  !== null ?  $request['order_note'] : null,
                    'customer_name' => $user->name,
                    'customer_email' => $user->email,
                    'customer_phone' => $user->phone,
                    'updated_at' => now(),
                    'created_at' => now(),
                ]);
            }

            return response()->json(['order'=>Orders::where('user_id',$request['user_id'])->get()]);
        } else {
            return response()->json(['order'=>'your order is on hold']);
        }
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
        return response()->json([$allOrders = Orders::with('product','store')->get()]);
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
    public function getOrder(Request $request)
    {
        $rules = [
            'user_id' => 'required',
        ];
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
        }
        
        return response()->json(['Orders' => $orders = Orders::where('user_id',$request->user_id)->with('product','store')->get()]);
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
        
        $Orders = Orders::where('user_id',$request->user_id)->delete();
        if($Orders == 1) {
            return response()->json(['status' =>true,'order'=>'found and removed.']);
        }

        return response()->json(['status' =>false,'order'=>'not found']);
    }

    /**
    * @OA\Get(
    *     path="/api/filter/order",
    *     summary="Request that search via order name",
    *     description="",
    *     tags={"Order Section"},
    *     @OA\Parameter(
    *        name="user_id",
    *        in="query",
    *        description="Please write order user Id",
    *        allowEmptyValue=true,
    *     ),
    *     @OA\Parameter(
    *        name="email",
    *        in="query",
    *        description="Please write order user email",
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
    public function filterOrder(Request $request)
    {
        if(!is_null($request['user_id']) || !is_null($request['email']) ) {
            $orderFilter = Orders::where('user_id',$request->user_id)->orWhere('customer_email', $request['email'])->with('product','store')->get();
           
            return response()->json(['order' => $orderFilter]);
        }

        return response()->json(['order' => []]);
    }
}