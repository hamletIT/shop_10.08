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

class ApiProductController extends BaseController
{
    /** 
    * @OA\Post(
    *     path="/api/create/product",
    *     summary="Request that added a new product",
    *     description="",
    *     tags={"Product Section"},
    *     @OA\Parameter(
    *        name="name",
    *        in="query",
    *        description="Provide product name",
    *        required=true,
    *        allowEmptyValue=true,
    *     ),
    *     @OA\Parameter(
    *        name="type",
    *        in="query",
    *        description="Provide product type",
    *        required=true,
    *        allowEmptyValue=true,
    *     ),
    *     @OA\Parameter(
    *        name="description",
    *        in="query",
    *        description="Provide product description",
    *        required=true,
    *        allowEmptyValue=true,
    *     ),
    *     @OA\Parameter(
    *        name="photoFileName",
    *        in="query",
    *        description="Provide product photo file Name",
    *        required=true,
    *        allowEmptyValue=true,
    *     ),
    *     @OA\Parameter(
    *        name="size",
    *        in="query",
    *        description="Provide product size",
    *        required=true,
    *        allowEmptyValue=true,
    *     ),
    *     @OA\Parameter(
    *        name="status",
    *        in="query",
    *        description="Provide product status",
    *        required=true,
    *        allowEmptyValue=true,
    *     ),
    *     @OA\Parameter(
    *        name="standardCost",
    *        in="query",
    *        description="Provide product standard Cost",
    *        required=true,
    *        allowEmptyValue=true,
    *     ),
    *     @OA\Parameter(
    *        name="listprice",
    *        in="query",
    *        description="Provide product list price",
    *        required=true,
    *        allowEmptyValue=true,
    *     ),
    *     @OA\Parameter(
    *        name="price",
    *        in="query",
    *        description="Provide product price",
    *        required=true,
    *        allowEmptyValue=true,
    *     ),
    *     @OA\Parameter(
    *        name="totalPrice",
    *        in="query",
    *        description="Provide product total price",
    *        required=true,
    *        allowEmptyValue=true,
    *     ),
    *     @OA\Parameter(
    *        name="weight",
    *        in="query",
    *        description="Provide product weight",
    *        required=true,
    *        allowEmptyValue=true,
    *     ),
    *     @OA\Parameter(
    *        name="totalQty",
    *        in="query",
    *        description="Provide product totalQty",
    *        required=true,
    *        allowEmptyValue=true,
    *     ),
    *     @OA\Parameter(
    *        name="color",
    *        in="query",
    *        description="Provide product color",
    *        required=true,
    *        allowEmptyValue=true,
    *     ),
    *     @OA\Parameter(
    *        name="store_id",
    *        in="query",
    *        description="Provide product store ID or provide 1 for create default one",
    *        required=true,
    *        allowEmptyValue=true,
    *     ),
    *     @OA\Parameter(
    *        name="category_id",
    *        in="query",
    *        description="provide category ID or provide 1 for create default one",
    *        required=true,
    *        allowEmptyValue=true,
    *     ),
    *     @OA\Parameter(
    *        name="sub_category_id",
    *        in="query",
    *        description="provide category ID or let empty or provide 1 for create default one",
    *        required=true,
    *        allowEmptyValue=true,
    *     ),
    *     @OA\Parameter(
    *        name="child_sub_category_id",
    *        in="query",
    *        description="Provide product child sub category ID or let empty or provide 1 for create default one",
    *        required=true,
    *        allowEmptyValue=true,
    *     ),
    *     @OA\Response(
    *         response=200,
    *         description="OK",
    *         @OA\MediaType(
    *             mediaType="application/json",
    *         )
    *     ),
    *     @OA\Response(
    *          response=401,
    *          description="Unauthenticated",
    *     ),
    * 
    *     @OA\Response(
    *          response=403,
    *          description="Forbidden"
    *     ),
    *     @OA\Response(
    *          response=429,
    *          description="validation error"
    *     )
    * )
    */
    public function createProduct(Request $request)
    {
         $rules = [
            'name' => 'required',
            'type' => 'required',
            'description' => 'required',
            'photoFileName' => 'required',
            'size' => 'required',
            'status' => 'required|numeric|integer',
            'standardCost' => 'required|numeric|integer',
            'listprice' => 'required|numeric|integer',
            'price' => 'required|numeric|integer',
            'totalPrice' => 'required|numeric|integer',
            'weight' => 'required|numeric|integer',
            'totalQty' => 'required|numeric|integer',
            'store_id' => 'required|numeric|integer',
        ];
         $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
        }
        $randomNumber = rand(config('app.rand_min'),config('app.rand_max'));
        $product = Products::insertGetId([
            'store_id' => $request['store_id'],
            'name' => $request['name'],
            'productNumber' => $randomNumber,
            'rating' => 0,
            'color' => $request['color'],
            'type' => $request['type'],
            'description' => $request['description'],
            'photoFileName' => $request['photoFileName'],
            'photoFilePath' => 'test',
            'size' => $request['size'],
            'status' => $request['status'],
            'standardCost' => $request['standardCost'],
            'listprice' => $request['listprice'],
            'totalPrice' => $request['totalPrice'],
            'weight' => $request['weight'],
            'totalQty' => $request['totalQty'],
            'sellStartDate' => Carbon::now(config('app.timezone_now'))->toDateTimeString(),
            'sellEndDate' => Carbon::now(config('app.timezone_now'))->addYear()->format('Y-m-d H-i-m'),
            'updated_at' => now(),
            'created_at' => now(),
        ]);
        Prices::insertGetId([
            'product_id' => $product,
            'title' => $request['name'],
            'productPrice' => $request['price'],
            'status' => $request['status'],
            'updated_at' => now(),
            'created_at' => now(),
        ]);
        pivot_categories_products::create([
            'category_id' => $request['category_id'],
            'product_id' => $product,
            'updated_at' => now(),
            'created_at' => now(),
        ]);
        if(isset($request->sub_category_id) && $request->sub_category_id !== 'null')
        {
            pivot_sub_categories_products::insertGetId([
                'sub_category_id' => $request->sub_category_id,
                'category_id' => $request['category_id'],
                'product_id' => $product,
                'updated_at' => now(),
                'created_at' => now(),
            ]);
        }
        if(isset($request->child_sub_category_id) && $request->child_sub_category_id !== 'null')
        {
            pivot_child_sub_categories::insertGetId([
                'sub_category_id' => $request->sub_category_id,
                'child_sub_category_id' => $request['child_sub_category_id'],
                'product_id' => $product,
                'updated_at' => now(),
                'created_at' => now(),
            ]);
        }
        $image = array();
        $products = Products::where('id',$product)->first();
        if($file = $request->file('image')){
            foreach($file as $file){
                $image_name = md5(rand(1000,10000));
                $ext = strtolower($file->getClientOriginalExtension());
                $image_full_name = $image_name.'.'.$ext;
                if (!File::exists('Images'.'/'.$products['photoFileName'])) {
                    File::makeDirectory('Images'.'/'.$products['photoFileName']);
                }
                $uploade_path = public_path('Images'.'/'.$products['photoFileName']);
                $image_url = $uploade_path.$image_full_name;
                $file->move($uploade_path,$image_full_name);
                $image[] = $image_url;
                Photos::create([
                    'name' => $image_full_name,
                    'path' => $uploade_path,
                    'product_id' => $products->id,
                    'updated_at' => now(),
                    'created_at' => now(),
                ]);
            }
        }

        return response()->json(['category' => BigStores::with(
            [
            'bigStoreImages',
            'categories',
            'categories.categoryImages',
            'categories.categories',
            'categories.categories.subCategoryImages',
            'categories.categories.categories.ChildsubCategoryImages',
            'categories.categories.categories.products',
            'categories.categories.categories.products.store',
            'categories.categories.categories.products.productPrice',
            'categories.categories.categories.products.productImages',
            'categories.categories.categories.products.productOptions',
            'categories.categories.categories.products.productOptions.optionImages'
            ]
        )->get()]);
    }

    /** 
     * @OA\Post(
     *     path="/api/update/product",
     *     summary="Request which updating something regarding product",
     *     description="",
     *     tags={"Product Section"},
     *     @OA\Parameter(
     *        name="productNumber",
     *        in="query",
     *        description="Please write product number",
     *        required=true,
     *        allowEmptyValue=true,
     *     ),
     *     @OA\Parameter(
     *        name="product_id",
     *        in="query",
     *        description="Please write product id",
     *        required=true,
     *        allowEmptyValue=true,
     *     ),
     *     @OA\Parameter(
     *        name="name",
     *        in="query",
     *        description="For example please write new product name",
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
    public function updateProduct(Request $request)
    {
         $rules = [
            'productNumber' => 'required',
            'product_id' => 'required',
        ];
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
        }

        $product = Products::where('id',$request->product_id)->where('productNumber',$request->productNumber)->with(['productPrice','productImages','productOptions','productOptions.optionImages'])->first();
        if ($product == null) {
            return response()->json(['status'=>'No data found for these {id:'.$request->id.', code:'.$request->productNumber.'']);
        } else {
            $input = $request->all();
            $product->update($input);
           
            Prices::where('product_id',$product->id)->update([
                'productPrice'=>$request['price'],
            ]);
            if ($product) {
                return response()->json(['updated'=>$product,'update status'=>true]);
            } else {
                return response()->json(['update'=>$product,'No data found for these {id:'.$request->id.', code:'.$request->productNumber.'}, parameters'=>false]);
            }
        }
    }

    /**
    * @OA\Post(
    *     path="/api/delete/product",
    *     summary="Request which deletes product",
    *     description="",
    *     tags={"Product Section"},
    *     @OA\Parameter(
    *        name="productNumber",
    *        in="query",
    *        description="Please write product number",
    *        required=true,
    *        allowEmptyValue=true,
    *     ),
    *     @OA\Parameter(
    *        name="product_id",
    *        in="query",
    *        description="Please write product id",
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
    public function deleteProduct(Request $request)
    {
        $rules = [
            'productNumber' => 'required',
            'product_id' => 'required',
        ];
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
        }
        $product = Products::where('id',$request->product_id)->where('productNumber',$request->productNumber)->first();
        if ($product == null) {
            return response()->json(['product'=>'No data found']);
        }
        
        File::deleteDirectory(public_path('Images'.'/'.$product['photoFileName']));
        File::deleteDirectory(public_path('Option_Images'.'/'.$optionOfProduct['photoFileName']));

        if ($product == null) {
            return response()->json(['status'=>'No data found for these {id:'.$request->id.', code:'.$request->productNumber.'']);
        } else {
            $product->delete();
            
            return response()->json(['deleted'=>true,'option Of Product deleted'=>true]);
        }
    }

    /**
    * @OA\Post(
    *     path="/api/delete/photos/product",
    *     summary="Request which deleting images",
    *     description="",
    *     tags={"Product Section"},
    *     @OA\Parameter(
    *        name="productNumber",
    *        in="query",
    *        description="Please write product number",
    *        required=true,
    *        allowEmptyValue=true,
    *     ),
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
    public function deletePhotosProduct(Request $request)
    {
        $rules = [
            'productNumber' => 'required',
            'product_id' => 'required',
        ];
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
        }
        $product = Products::where('id',$request->product_id)->where('productNumber',$request->productNumber)->first();
        if ($product == null) {
            return response()->json(['product'=>'No data found']);
        }
        $photo = Photos::where('product_id',$product->id)->get();
        
        foreach ($photo as $key => $item) {
                unlink($item['path'].'/'.$item['name']);
                $item->delete();
        }

        return response()->json(['deleted'=>true,'all photos Of Product deleted'=>true]);
    }

    /**
    * @OA\Post(
    *     path="/api/delete/photo/product",
    *     summary="Request which deleting certain image",
    *     description="",
    *     tags={"Product Section"},
    *     @OA\Parameter(
    *        name="product_id",
    *        in="query",
    *        description="Please write product ID",
    *        required=true,
    *        allowEmptyValue=true,
    *     ),
    *     @OA\Parameter(
    *        name="photo_name",
    *        in="query",
    *        description="Please write photo name",
    *        required=true,
    *        allowEmptyValue=true,
    *     ),
    *     @OA\Parameter(
    *        name="productNumber",
    *        in="query",
    *        description="Please write product number",
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
    public function deletePhotoByNameOfProduct(Request $request)
    {
        $rules = [
            'productNumber' => 'required',
            'product_id' => 'required',
            'photo_name' => 'required',
        ];
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
        }
        $product = Products::where('id',$request->product_id)->where('productNumber',$request->productNumber)->first();
        if ($product == null) {
            return response()->json(['product'=>'No data found']);
        }
        $photo = Photos::where('product_id',$product->id)->where('name',$request['photo_name'])->first();
        
        unlink($photo['path'].'/'.$photo['name']);
        $photo->delete();

        return response()->json(['deleted'=>true,'photo Of Product deleted'=>true]);
       
    }

    /**
     * Validate and add a photos
     */
    public function addPhotosByNameOfProduct(Request $request)
    {
        $rules = [
            'product_id' => 'required',
            'image.*' => 'required',
        ];
       
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
        }
        $image = array();
        $product = Products::where('id',$request->product_id)->first();
        if($file = $request->file('image')){
            foreach($file as $file){
                $image_name = md5(rand(1000,10000));
                $ext = strtolower($file->getClientOriginalExtension());
                $image_full_name = $image_name.'.'.$ext;
                if (!File::exists($product['photoFileName'])) {
                    File::makeDirectory($product['photoFileName']);
                }
                $uploade_path = public_path($product['photoFileName']);
                $image_url = $uploade_path.$image_full_name;
                $file->move($uploade_path,$image_full_name);
                $image[] = $image_url;
                Photos::create([
                    'name' => $image_full_name,
                    'path' => $uploade_path,
                    'product_id' => $product->id,
                    'updated_at' => now(),
                    'created_at' => now(),
                ]);
            }
        }

        return response()->json(['product'=>Products::where('id',$request->product_id)->with(['productPrice','productImages','productOptions','productOptions.optionImages'])->first()]);
    }

    /**
    * @OA\Get(
    *     path="/api/filter/product",
    *     summary="Request that search via name",
    *     description="",
    *     tags={"Product Section"},
    *     @OA\Parameter(
    *        name="name",
    *        in="query",
    *        description="Please write product name",
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
    public function filterProduct(Request $request)
    {
        if(!is_null($request['name'])) {

            $productFilter = Products::Where('name', 'LIKE', '%'.$request['name'].'%')->with(['productPrice','productImages','productOptions','productOptions.optionImages'])->get();
           
            return response()->json(['product' => $productFilter]);
        }

        return response()->json(['product' => []]);
    }

    /**
    * @OA\Get(
    *     path="/api/get/store/products",
    *     summary="Request which returns products with photos user",
    *     description="",
    *     tags={"Product Section"},
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
    public function getstoreProducts(Request $request)
    {
        $rules = [
            'store_id' => 'required',
        ];
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
        }
       
        $products = Products::where('store_id',$request['store_id'])->with(['store','productPrice','productImages','productOptions','productOptions.optionImages'])->get();
        
        return response()->json(['products' => $products]);
    
    }

    /**
    * @OA\Get(
    *     path="/api/get/photosAnd/products",
    *     summary="Request which returns products with photos",
    *     description="",
    *     tags={"Product Section"},
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
    public function getPhotosAndProducts(Request $request)
    {
        return response()->json(['products' => Products::with(['productPrice','productImages','productOptions','productOptions.optionImages'])->get()]);
    }

    // /**
    // * @OA\Get(
    // *     path="/api/get/single/photosAnd/products",
    // *     summary="Request which returns products with photos single store",
    // *     description="",
    // *     tags={"Product Section"},
    // *     @OA\Parameter(
    // *        name="store_id",
    // *        in="query",
    // *        description="Please write store ID",
    // *        required=true,
    // *        allowEmptyValue=true,
    // *     ),
    // *     @OA\Response(
    // *        response=200,
    // *        description="OK",
    // *        @OA\MediaType(
    // *            mediaType="application/json",
    // *        )
    // *     ),
    // *     @OA\Response(
    // *         response=401,
    // *         description="Unauthenticated",
    // *     ),
    // *     @OA\Response(
    // *         response=403,
    // *         description="Forbidden"
    // *     ),
    // *     @OA\Response(
    // *         response=429,
    // *         description="validation error"
    // *     )
    // *   ),
    // * )
    // */
    // public function getSingleStorePhotosAndProducts(Request $request)
    // {
    //      $rules = [
    //         'store_id' => 'required',
    //     ];
    //     $validator = Validator::make($request->all(), $rules);

    //     if ($validator->fails()) {
    //         return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
    //     }
        
    //     $products = Products::where('store_id',$request['store_id'])->with(['productPrice','productImages','productOptions','productOptions.optionImages'])->first();
    
    //     return response()->json(['product' => $products]);
    // }

    /**
    * @OA\Get(
    *     path="/api/get/single/product/andphotos",
    *     summary="Request which returns single product with photos",
    *     description="",
    *     tags={"Product Section"},
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
    public function getSingleProductAndPhotos(Request $request)
    {
         $rules = [
            'product_id' => 'required',
        ];
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
        }
        
        $product = Products::where('id',$request['product_id'])->with(['productPrice','productImages','productOptions','productOptions.optionImages'])->first();
    
        return response()->json(['product' => $product]);
    }

     /**
    * @OA\Get(
    *     path="/api/get/product/list",
    *     summary="Request which returns categories with products, store, photos and options",
    *     description="",
    *     tags={"Product Section"},
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
    public function getProductList(Request $request)
    {
        return response()->json(['category' => BigStores::with(
            [
            'bigStoreImages',
            'categories',
            'categories.categoryImages',
            'categories.categories',
            'categories.categories.subCategoryImages',
            'categories.categories.categories.ChildsubCategoryImages',
            'categories.categories.categories.products',
            'categories.categories.categories.products.store',
            'categories.categories.categories.products.productPrice',
            'categories.categories.categories.products.productImages',
            'categories.categories.categories.products.productOptions',
            'categories.categories.categories.products.productOptions.optionImages'
            ]
        )->get()]);
    }
}