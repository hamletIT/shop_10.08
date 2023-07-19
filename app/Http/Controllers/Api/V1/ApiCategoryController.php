<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BigStores;
use App\Models\BigStorePhotos;
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
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Routing\Controller as BaseController;

class ApiCategoryController extends BaseController
{
    /** 
     * @OA\Post(
     *     path="/api/create/category",
     *     summary="Request that added a new category",
     *     description="",
     *     tags={"Category Section"},
     *     @OA\Parameter(
     *        name="title",
     *        in="query",
     *        description="Please write a category title",
     *        required=true,
     *        allowEmptyValue=true,
     *     ),
    *     @OA\Parameter(
     *        name="photoFileName",
     *        in="query",
     *        description="Please write a category Photo File Name",
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
    public function createCategory(Request $request)
    {
        $rules = [
            'title'=>'required|unique:categories,title',
            'photoFileName' => 'required|unique:categories,photoFileName',
            'big_store_id' => 'required',
        ];
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
        }

        $category = Category::insertGetId([
            'big_store_id' => $request['big_store_id'],
            'title' => $request['title'],
            'status' => 'active',
            'rating' => 0,
            'photoFileName' => $request['photoFileName'],
            'photoFilePath' => 'test',
            'updated_at' => now(),
            'created_at' => now(),
        ]);
        $image = array();
        $category_photos = Category::where('id',$category)->first();
        if($file = $request->file('image')){
            foreach($file as $file){
                $image_name = md5(rand(1000,10000));
                $ext = strtolower($file->getClientOriginalExtension());
                $image_full_name = $image_name.'.'.$ext;
                if (!File::exists('Category_images'.'/'.$category_photos['photoFileName'])) {
                    File::makeDirectory('Category_images'.'/'.$category_photos['photoFileName']);
                }
                $uploade_path = public_path('Category_images'.'/'.$category_photos['photoFileName']);
                $image_url = $uploade_path.$image_full_name;
                $file->move($uploade_path,$image_full_name);
                $image[] = $image_url;
                CategoryPhotos::create([
                    'name' => $image_full_name,
                    'path' => $uploade_path,
                    'category_id' => $category_photos->id,
                    'updated_at' => now(),
                    'created_at' => now(),
                ]);
            }
        }

        return response()->json(['categories'=>Category::with(['categoryImages'])->get()]);
    }

    /** 
     * @OA\Post(
     *     path="/api/update/category",
     *     summary="Request which updating something regarding category",
     *     description="",
     *     tags={"Category Section"},
     *     @OA\Parameter(
     *        name="title",
     *        in="query",
     *        description="Please write a category title",
     *        required=true,
     *        allowEmptyValue=true,
     *     ),
     *     @OA\Parameter(
     *        name="category_id",
     *        in="query",
     *        description="Please write a category ID",
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
    public function updateCategory(Request $request)
    {
        $rules = [
            'title'=>'required',
            'category_id' => 'required',
        ];
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
        }

        $singleCategory = Category::where('id',$request['category_id'])->first();
        if ($singleCategory == null) {
            return response()->json(['Response'=>$request['category_id'].' Id category not found']);
        }

        $category = Category::where('id',$request['category_id'])->update([
            'title' => $request['title']
        ]);

        return response()->json(['categories'=>Category::where('id',$request['category_id'])->with(['categoryImages'])->get()]);
    }

    /** 
     * @OA\Post(
     *     path="/api/delete/category",
     *     summary="Request which deletes category",
     *     description="",
     *     tags={"Category Section"},
     *     @OA\Parameter(
     *        name="category_id",
     *        in="query",
     *        description="Please write a category ID",
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
    public function deleteCategory(Request $request)
    {
        $rules = [
            'category_id' => 'required'
        ];
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
        }

        $singleCategory = Category::where('id',$request['category_id'])->first();
        if ($singleCategory == null) {
            return response()->json(['Response'=>$request['category_id'].' Id category not found']);
        }

        $categoryProduct = Category::where('status','001')->first();
        $categoryPivot = pivot_categories_products::where('category_id',$request['category_id'])->update([
            'category_id' => $categoryProduct->id,
        ]);
        $categoryPivot = pivot_sub_categories_products::where('category_id',$request['category_id'])->update([
            'category_id' => $categoryProduct->id,
        ]);

        $category = Category::where('id',$request['category_id'])->delete();
           
        return response()->json(['categories'=>Category::with(['categoryImages'])->get()]);
    }

    /** 
     * @OA\Get(
     *     path="/api/filter/catagory/byTitle",
     *     summary="Request which returns categories with products, photos and options",
     *     description="",
     *     tags={"Category Section"},
     *     @OA\Parameter(
     *        name="title",
     *        in="query",
     *        description="Please write a category title",
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
    public function filterCategoryByTitle(Request $request)
    {
        $rules = [
            'title' => 'required'
        ];
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
        }

        $category = Category::Where('title', 'LIKE', '%'.$request['title'].'%')->with(
            [
            'categoryImages',
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
        )->get();

        return response()->json(['category' => $category]);
    }

     /** 
     * @OA\Get(
     *     path="/api/get/catagories",
     *     summary="Request which returns categories",
     *     description="",
     *     tags={"Category Section"},
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
    public function getCategories(Request $request)
    {
        $category = BigStores::with(
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
        )->get();

        return response()->json(['category' => $category]);
    }

    /** 
     * @OA\Get(
     *     path="/api/get/catagories/with/sub/catagories",
     *     summary="Request which returns catagories with sub categories",
     *     description="",
     *     tags={"Category Section"},
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
    public function getCategoriesWithSubCategories(Request $request)
    {
         $categoryAndSubCategoriy = Category::with(
            [
            'categoryImages',
            'subCategory',
            'subCategory.subCategoryImages',
            'subCategory.products',
            'subCategory.products.store',
            'subCategory.products.productPrice',
            'subCategory.products.productImages',
            'subCategory.products.productOptions',
            'subCategory.products.productOptions.optionImages'
            ]
        )->get();

        return response()->json(['categoriesWithSubSubCategories' => $categoryAndSubCategoriy]);
    }
}
