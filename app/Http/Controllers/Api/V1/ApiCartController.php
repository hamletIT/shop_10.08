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
use App\Models\Photos;
use App\Models\OptionPhotos;
use App\Models\Applications;
use App\Models\Category;
use App\Models\SubCategory;
use App\Models\Prices;
use App\Models\pivot_categories_products;
use App\Models\pivot_sub_categories_products;
use App\Models\CategoryPhotos;
use App\Models\SubCategoryPhotos;
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

class ApiCartController extends BaseController
{
    public function __construct(
        public ApiVarableServices $apiVarableServices,
    ) {
       
    }
    /**
    * @OA\Post(
    *     path="/api/add/toCart",
    *     summary="Add To Cart",
    *     description="",
    *     tags={"Cart Section"},
    *     @OA\Parameter(
    *        name="product_id",
    *        in="query",
    *        description="Please write product ID",
    *        required=true,
    *        allowEmptyValue=true,
    *     ),
    *     @OA\Parameter(
    *        name="user_id",
    *        in="query",
    *        description="Please write user ID",
    *        required=true,
    *        allowEmptyValue=true,
    *     ),
    *     @OA\Parameter(
    *        name="totalQty",
    *        in="query",
    *        description="Please write totalQty",
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
    public function addToCart(Request $request)
    {
        // return response()->json($request->all());

        // $option = [
        //     1 => [
        //         "id" => 3, 
        //         "qty" => 2 
        //     ],
        //     2 => [
        //         "id" => 4, 
        //         "qty" => 2
        //     ]
        // ];
       
        $rules = [
            'user_id' => 'required',
            'product_id' => 'required',
            'totalQty' => 'required',
        ];
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
        }

        $product = Products::where('id',$request->product_id)->first();
        $cartProductNumber = Carts::where('product_id',$request->product_id)->first();
        if (is_null($product)) {
            return response()->json(['product'=>'product not found']);
        }
        $randomNumberForOption = rand(config('app.rand_min'),config('app.rand_max'));
       
        foreach($request->options as $value){
            $valueAsString = json_encode($value);
            if ($cartProductNumber !== null) {
               $number = $cartProductNumber->random_number;
            } else {
               $number = $randomNumberForOption;
            }
            Carts::insertGetId([
                'random_number' => $number,
                'status' => $product->status,
                'sessionStartDate' => Carbon::now(config('app.timezone_now'))->toDateTimeString(),
                'sessionEndDate' => Carbon::now(config('app.timezone_now'))->addWeeks(1)->toDateTimeString(),
                'totalQty' => $request->totalQty,
                'array_options' => $valueAsString,
                'product_id' => $product->id,
                'user_id' => $request->user_id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
        $cartResponse = $this->apiVarableServices->getCart($request->user_id);

        return response()->json($cartResponse);
    }

    /**
     * @OA\Get(
     *     path="/api/get/cart/products",
     *     summary="Get Cart Products",
     *     description="",
     *     tags={"Cart Section"},
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
     *    ),
     * )
     */
    public function getCartProducts(Request $request)
    {
        $rules = [
            'user_id' => 'required',
        ];
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
        }
        $userIsset = User::find($request->user_id);
        if ($userIsset == null) {
            return response()->json(["userCart" => []]);
        }
        $cartResponse = $this->apiVarableServices->getCart($request->user_id);

        return response()->json($cartResponse);
    }

    /**
    *  @OA\Post(
    *     path="/api/delete/cart/products",
    *     summary="Delete Cart Products",
    *     description="",
    *     tags={"Cart Section"},
    *     @OA\Parameter(
    *        name="product_id",
    *        in="query",
    *        description="Please write product ID",
    *        required=true,
    *        allowEmptyValue=true,
    *     ),
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
    public function deleteCartProducts(Request $request)
    {
        $rules = [
            'user_id' => 'required|numeric|integer',
            'product_id' => 'required|numeric|integer',
        ];
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
        }
        $userCart = Carts::where('user_id',$request->user_id)->where('product_id',$request->product_id)->first();

        if ($userCart == null) {
            return response()->json(['cart_products'=>'No data found']);
        } else {
            Carts::where('user_id',$request->user_id)->where('product_id',$request->product_id)->delete();

            $cartResponse = $this->apiVarableServices->getCart($request->user_id);

            return response()->json($cartResponse);
        }
    }

    /**
    *  @OA\Post(
    *     path="/api/add/quantity/forOne/Product",
    *     summary="Add Quantity For One Product",
    *     description="",
    *     tags={"Cart Section"},
    *     @OA\Parameter(
    *        name="product_id",
    *        in="query",
    *        description="Please write product ID",
    *        required=true,
    *        allowEmptyValue=true,
    *     ),
    *     @OA\Parameter(
    *        name="user_id",
    *        in="query",
    *        description="Please write user ID",
    *        required=true,
    *        allowEmptyValue=true,
    *     ),
    *     @OA\Parameter(
    *        name="qty",
    *        in="query",
    *        description="Please Write quantity How much do you want to add",
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
    public function AddQuantityForOneProduct(Request $request)
    {
        $rules = [
            'user_id' => 'required|numeric|integer',
            'product_id' => 'required|numeric|integer',
            'qty' => 'required|numeric|integer',
        ];
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
        }
        $updateCartProductQty = Carts::where('user_id', $request->user_id)->where('product_id', $request->product_id)->update(['totalQty' => $request->qty]);

        if ($updateCartProductQty) {
            $cartResponse = $this->apiVarableServices->getCart($request->user_id);

            return response()->json($cartResponse);
        }
       
        return response()->json(['update' => 'Failed to update']);
    }
}