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
use Illuminate\Routing\Controller as BaseController;

class ApiCartController extends BaseController
{
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
        $option = [
            1 => [
                "id" => 3, 
                "qty" => 2 
            ],
            2 => [
                "id" => 4, 
                "qty" => 3
            ]
        ];
       
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
        if (is_null($product)) {
            return response()->json(['product'=>'product not found']);
        }
        $randomNumberForOption = rand(config('app.rand_min'),config('app.rand_max'));
       
        foreach($option as $value){
            $valueAsString = json_encode($value);
            $cart = Carts::insertGetId([
                'random_number' => $randomNumberForOption,
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

        return response()->json(['product'=>Carts::find($cart)]);
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

        $cart = Carts::where('user_id',$request->user_id)->get()->groupBy('random_number');

        // return response()->json($cart);

        $randPrcies = [];
        
        foreach($cart as $key => $items)
        {
            $productTable = Products::with('productPrice')->where('id',$items[0]->product_id)->first(); // 10 , 4
            $prod_price = $productTable->productPrice[0]->productPrice * $items[0]->totalQty; // 40
            $randPrice = 0;

            foreach($items as $key => $item)
            {
                $option_array_price = json_decode($item->array_options);
                $option_price = json_decode($option_array_price->id); // 5, 6 * 4
                $option_qty = json_decode($option_array_price->qty);  
                $optionTable = Options::where('id',$option_price)->first();
                $qty_price = $option_qty * $optionTable->price * $items[0]->totalQty; 
                $randPrice += $qty_price;
            }
            $randPrcies['product_id: '.$items[0]->product_id] = $randPrice + $prod_price;
            $product[$items[0]->product_id] = $items[0];
        }

        
        
        return response()->json(['products'=>$product,'product prices'=>$randPrcies,'Total price:'=>array_sum($randPrcies)]);  
       
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
            'product_id' => 'required',
        ];
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
        }
        $userCart = Carts::where('product_id',$request->product_id)->first();

        if ($userCart == null) {
            return response()->json(['status'=>'No data found for these {id:'.$request->product_id.'}']);
        } else {
            $userCart->delete();
            return response()->json(['deleted'=>true]);
        }
    }

    /**
    *  @OA\Get(
    *     path="/api/filter/cart",
    *     summary="Request that search via product name",
    *     description="",
    *     tags={"Cart Section"},
    *     @OA\Parameter(
    *        name="name",
    *        in="query",
    *        description="Please write cart name",
    *        required=true,
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
    public function filterCart(Request $request)
    {
        if (!is_null($request['name'])) {
            $cartFilter = Carts::where('name', 'LIKE', '%'.$request['name'].'%')->with('product')->get();
          
            return response()->json(['cart' => $cartFilter]);
        }

        return response()->json(['cart' => []]);
    }
}