<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use App\Models\BigStores;
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

class ApiSubCategoryController extends BaseController
{
    public function __construct(
        public ApiVarableServices $apiVarableServices,
    ) {
       
    }
    /** 
     * @OA\Post(
     *     path="/api/create/sub/category",
     *     summary="Request that added a new sub category",
     *     description="",
     *     tags={"Sub Category Section"},
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
    public function createSubCategory(Request $request)
    {
         $rules = [
            'title'=>'required|unique:sub_categories,title',
            'photoFileName' => 'required|unique:sub_categories,photoFileName',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
        }
        $sub_category = SubCategory::insertGetId([
            'title' => $request['title'],
            'status' => 'active',
            'rating' => 0,
            'photoFileName' => $request['photoFileName'],
            'photoFilePath' => 'test',
            'updated_at' => now(),
            'created_at' => now(),
        ]);
        $image = array();
        $sub_category_photos = SubCategory::where('id',$sub_category)->first();
        if($file = $request->file('image')){
            foreach($file as $file){
                $image_name = md5(rand(1000,10000));
                $ext = strtolower($file->getClientOriginalExtension());
                $image_full_name = $image_name.'.'.$ext;
                if (!File::exists('Sub_category_images'.'/'.$sub_category_photos['photoFileName'])) {
                    File::makeDirectory('Sub_category_images'.'/'.$sub_category_photos['photoFileName']);
                }
                $uploade_path = public_path('Sub_category_images'.'/'.$sub_category_photos['photoFileName']);
                $image_url = $uploade_path.$image_full_name;
                $file->move($uploade_path,$image_full_name);
                $image[] = $image_url;
                SubCategoryPhotos::create([
                    'name' => $image_full_name,
                    'path' => $uploade_path,
                    'sub_category_id' => $sub_category_photos->id,
                    'updated_at' => now(),
                    'created_at' => now(),
                ]);
            }
        }

        return response()->json(['subCategories'=>BigStores::with(
            $this->apiVarableServices->StructureOfTheStandardSchema()
        )->get()]);
    }

    /** 
     * @OA\Post(
     *     path="/api/update/sub/category",
     *     summary="Request which updating something regarding Sub category",
     *     description="",
     *     tags={"Sub Category Section"},
     *     @OA\Parameter(
     *        name="title",
     *        in="query",
     *        description="Please write a category title",
     *        required=true,
     *        allowEmptyValue=true,
     *     ),
     *     @OA\Parameter(
     *        name="sub_category_id",
     *        in="query",
     *        description="Please write a sub category ID",
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
    public function updateSubCategory(Request $request)
    {
        $rules = [
            'title'=>'required',
            'sub_category_id' => 'required',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
        }
        SubCategory::where('id',$request['sub_category_id'])->update([
            'title' => $request['title']
        ]);

        return response()->json(['subCategories'=>BigStores::with(
            $this->apiVarableServices->StructureOfTheStandardSchema()
        )->get()]);
    }

    /** 
     * @OA\Post(
     *     path="/api/delete/sub/category",
     *     summary="Request which deletes sub category",
     *     description="",
     *     tags={"Sub Category Section"},
     *     @OA\Parameter(
     *        name="sub_category_id",
     *        in="query",
     *        description="Please write a sub category ID",
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
    public function deleteSubCategory(Request $request)
    {
        $rules = [
            'sub_category_id' => 'required',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
        }
        $subCategoryProduct = SubCategory::where('status','001')->first();
        pivot_sub_categories_products::where('sub_category_id',$request['sub_category_id'])->update([
            'sub_category_id' => $subCategoryProduct->id,
        ]);
        Category::where('id',$request['category_id'])->delete();
           
        return response()->json(['deleted'=>true]);
    }

    /** 
     * @OA\Get(
     *     path="/api/filter/sub/catagory/byTitle",
     *     summary="Request which returns filtered sub categories",
     *     description="",
     *     tags={"Sub Category Section"},
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
    public function filterSubCategoryByTitle(Request $request)
    {
        $rules = [
            'title' => 'required'
        ];
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
        }

        return response()->json(['subCategories' => $this->apiVarableServices->filteredSubCategory($request['title'])]);
    }

     /** 
     * @OA\Get(
     *     path="/api/get/sub/catagories",
     *     summary="Request which returns sub categories",
     *     description="",
     *     tags={"Sub Category Section"},
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
    public function getSubCategories(Request $request)
    {
        return response()->json(['subCategories'=>BigStores::with(
           $this->apiVarableServices->StructureOfTheStandardSchema()
        )->get()]);
    }
}

