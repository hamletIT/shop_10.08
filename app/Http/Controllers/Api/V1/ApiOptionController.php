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

class ApiOptionController extends BaseController
{
    /**
     * @OA\Post(
     *    path="/api/create/option",
     *    summary="Request which create options",
     *    description="",
     *    tags={"Option Section"},
     *    @OA\Parameter(
     *       name="product_id",
     *       in="query",
     *       description="Please write product id",
     *       required=true,
     *        allowEmptyValue=true,
     *    ),
     *    @OA\Parameter(
     *       name="name",
     *       in="query",
     *       description="Please write name",
     *       required=true,
     *        allowEmptyValue=true,
     *    ),
     *    @OA\Parameter(
     *       name="name_info",
     *       in="query",
     *       description="Please write name information",
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
     *    @OA\Parameter(
     *       name="price",
     *       in="query",
     *       description="Please write status",
     *       required=true,
     *        allowEmptyValue=true,
     *    ),
     *    @OA\Parameter(
     *       name="photoFileName",
     *       in="query",
     *       description="Please write Photo File Name",
     *       required=true,
     *        allowEmptyValue=true,
     *    ),
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
    public function createOptionsForProduct(Request $request)
    {
        $rules = [
            'product_id' => 'required|numeric|integer',
            'name' => 'required',
            'information' => 'required',
            'status' => 'required',
            'price' => 'required',
            'photoFileName' => 'required',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return redirect()->back()->with(array('errors' => $validator->getMessageBag()->toArray()));
        }
        $option = Options::insertGetId([
            'product_id' => $request['product_id'],
            'name' => $request['name'],
            'name_info' => $request['information'],
            'status' => $request['status'],
            'price' => $request['price'],
            'photoFilePath' => 'test',
            'photoFileName' => $request['photoFileName'],
            'updated_at' => now(),
            'created_at' => now(),
        ]);
        $image = array();
        $option_photos = Options::where('id',$option)->first();
        if($file = $request->file('image')){
            foreach($file as $file){
                $image_name = md5(rand(1000,10000));
                $ext = strtolower($file->getClientOriginalExtension());
                $image_full_name = $image_name.'.'.$ext;
                if (!File::exists('Option_Images'.'/'.$option_photos['photoFileName'])) {
                    File::makeDirectory('Option_Images'.'/'.$option_photos['photoFileName']);
                }
                $uploade_path = public_path('Option_Images'.'/'.$option_photos['photoFileName']);
                $image_url = $uploade_path.$image_full_name;
                $file->move($uploade_path,$image_full_name);
                $image[] = $image_url;
                OptionPhotos::create([
                    'name' => $image_full_name,
                    'path' => $uploade_path,
                    'option_id' => $option_photos->id,
                    'updated_at' => now(),
                    'created_at' => now(),
                ]);
            }
        }
        $options = Options::with(['optionImages'])->get();

        return response()->json(['options'=>$options]);
    }

    /**
    * @OA\Get(
    *     path="/api/get/all/option",
    *     summary="Request which returns options",
    *     description="",
    *     tags={"Option Section"},
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
    public function getOptions(Request $request)
    {
        return response()->json(['options'=>Options::with(['optionImages'])->get()]);
    }

    /**
    * @OA\Get(
    *     path="/api/get/option",
    *     summary="Request which returns certein options of certein product",
    *     description="",
    *     tags={"Option Section"},
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
    public function getOption(Request $request)
    {
        $rules = [
            'product_id' => 'required|numeric|integer',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return redirect()->back()->with(array('errors' => $validator->getMessageBag()->toArray()));
        }
        $option = Options::with(['optionImages'])->where('product_id', $request['product_id'])->with('product' ,function($query) use ($request) {
            $query->where('id', $request['product_id']);
        })->get();
        
        return response()->json(['option'=>$option]);
    }

    /**
     * @OA\Get(
     *     path="/api/filter/option",
     *     summary="Request that search via option name",
     *     description="",
     *     tags={"Option Section"},
     *     @OA\Parameter(
     *        name="name",
     *        in="query",
     *        description="Please write option name",
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
    public function filterOption(Request $request)
    {
        if (!is_null($request['name'])) {
            $optionFilter = Options::where('name', 'LIKE', '%'.$request['name'].'%')->with('optionImages')->get();
          
            return response()->json(['options' => $optionFilter]);
        }

        return response()->json(['options' => []]);
    }
}