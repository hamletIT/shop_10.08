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
use App\Models\Payments;
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

class ApiPaymentController extends BaseController
{   
    /**
    * @OA\Post(
    *     path="/api/create/payment",
    *     summary="Request prepares payments",
    *     description="",
    *     tags={"Payment Section"},
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
    public function createPayment(Request $request)
    {
        $rules = [
            'user_id' => 'required|numeric|integer',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
        }
        $arrayPrice=[];
        $getUserOrders = Orders::where('user_id',$request->user_id)->get();
        $userHistoryPayments = Payments::where('user_id',$request->user_id)->get();

        foreach ($getUserOrders as $key => $value) {
           if ($value->status == 0) {
                array_push($arrayPrice, $value['totalPrice']);
            }
        }
       
        if (count($getUserOrders) > 0){
            if( count($userHistoryPayments) > 0) {
            
                return response()->json(['payment' => 'You still have not paying for the order.']);

            } else {
                $payment = Payments::insertGetId([
                    'user_id' => $request->user_id,
                    'totalFinalPrice' => array_sum($arrayPrice),
                    'payment_status' => 'Pending',
                    'status' => 0,
                    'updated_at' => now(),
                    'created_at' => now(),
                ]);

                return response()->json(['payment'=>Payments::find($payment)]);
            }
          
        } else {
            return response()->json(['orders' => "You don't have an order to make a purchase."]);
        }
    }

    /**
    * @OA\Get(
    *     path="/api/get/payment",
    *     summary="Request which returns payments",
    *     description="",
    *     tags={"Payment Section"},
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
    public function getPayment(Request $request)
    {
        return response()->json(['history payment' => Payments::where('user_id',$request->user_id)->where('payment_status','Accepted')->get()]);
    }

    /**
    * @OA\Post(
    *    path="/api/accept/purchase",
    *    summary="Request confirmation",
    *    description="",
    *    tags={"Payment Section"},
    *    @OA\Parameter(
    *       name="payment_id",
    *       in="query",
    *       description="Please write payment ID",
    *       required=true,
    *        allowEmptyValue=true,
    *    ),
    *    @OA\Parameter(
    *       name="user_id",
    *       in="query",
    *       description="Please write user ID",
    *       required=true,
    *        allowEmptyValue=true,
    *    ),
    *    @OA\Parameter(
    *       name="status",
    *       in="query",
    *       description="Please write status",
    *       required=true,
    *        allowEmptyValue=true,
    *    ),
    *    @OA\Response(
    *       response=200,
    *       description="OK",
    *       @OA\MediaType(
    *           mediaType="application/json",
    *       )
    *    ),
    *    @OA\Response(
    *        response=401,
    *        description="Unauthenticated",
    *    ),
    *    @OA\Response(
    *        response=403,
    *        description="Forbidden"
    *    ),
    *    @OA\Response(
    *        response=429,
    *        description="validation error"
    *    )
    *  ),
    * )
    */
    public function acceptPurchase(Request $request)
    {
         $rules = [
            'user_id' => 'required|numeric|integer',
            'payment_id' => 'required|numeric|integer',
            'status' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
        }
        if ($request->status == 200 && isset($request->user_id) && isset($request->payment_id)) {
            $paymentUser = Payments::where('user_id',$request->user_id)->where('id',$request->payment_id)->first();
            if ($paymentUser ==  null) {
                return response()->json(['Payment'=>'not found']);
            }
            
            $acceptPayment = Payments::where('user_id',$request->user_id)->where('id',$request->payment_id)->update([
                'payment_status' => 'Accepted',
                'status' => 1,
            ]);

            $deleteOrder = Orders::where('user_id',$request->user_id)->where('status',0)->update([
                'status'=>10,
            ]);
            if ($acceptPayment) {
                return response()->json(['updated'=>true, 'parameters'=>Payments::where('id',$request->payment_id)->first()]);
            } else {
                return response()->json(['updated'=>false, 'parameters'=>false]);
            }
        }
    }
}