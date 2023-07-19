<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;
use App\Models\ChildSubCategory;
use App\Models\ChildSubCategoryPhotos;

class ApiChildSubCategoryController extends Controller
{   
    /**
    *  @OA\Get(
    *     path="/api/get/child/sub/category",
    *     summary="Request that get single child sub category",
    *     description="",
    *     tags={"Child Sub Category Section"},
    *     @OA\Parameter(
    *        name="child_sub_category_id",
    *        in="query",
    *        description="Please write child sub category id",
    *        required=true,
    *        allowEmptyValue=true,
    *     ),
    *     @OA\Response(
    *        response=200,
    *        description="Successful response",
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
    *         description="Validation error"
    *     )
    *   ),
    */
    public function getChildSubCategory(Request $request)
    {
        $rules = [
            'child_sub_category_id' => 'required',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
        }

        return response()->json(['ChildSubCategory' => ChildSubCategory::where('id',$request['child_sub_category_id'])->with(['ChildsubCategoryImages','products'])->get()]);
    }

    /** 
     * @OA\Get(
     *     path="/api/get/child/sub/categories",
     *     summary="Request that get all child sub category",
     *     description="",
     *     tags={"Child Sub Category Section"},
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
    public function getChildSubCategories(Request $request)
    {
        return response()->json(['ChildSubCategory' => ChildSubCategory::with(['ChildsubCategoryImages','products'])->get()]);
    }

    /**
    * @OA\Post(
    *     path="/api/add-ChildSubCategory",
    *     summary="Request that add child sub category",
    *     description="",
    *     tags={"Child Sub Category Section"},
    *     @OA\Parameter(
    *        name="title",
    *        in="query",
    *        description="Please write category title",
    *        required=true,
    *        allowEmptyValue=true,
    *     ),
    *     @OA\Parameter(
    *        name="photoFileName",
    *        in="query",
    *        description="Please write category photoFileName",
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
    *         description="Validation error"
    *     )
    *   ),
    */
    public function addChildSubCategory(Request $request)
    {
        $rules = [
            'title'=>'required|unique:sub_categories,title',
            'photoFileName' => 'required|unique:sub_categories,photoFileName',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
        }
        $child_sub_category = ChildSubCategory::insertGetId([
            'title' => $request['title'],
            'status' => 'active',
            'rating' => 0,
            'photoFileName' => $request['photoFileName'],
            'photoFilePath' => 'test',
            'updated_at' => now(),
            'created_at' => now(),
        ]);
        $image = array();
        $child_sub_category_photos = ChildSubCategory::where('id',$child_sub_category)->first();
        if($file = $request->file('image')){
            foreach($file as $file){
                $image_name = md5(rand(1000,10000));
                $ext = strtolower($file->getClientOriginalExtension());
                $image_full_name = $image_name.'.'.$ext;
                if (!File::exists('Child_sub_category_images'.'/'.$child_sub_category_photos['photoFileName'])) {
                    File::makeDirectory('Child_sub_category_images'.'/'.$child_sub_category_photos['photoFileName']);
                }
                $uploade_path = public_path('Child_sub_category_images'.'/'.$child_sub_category_photos['photoFileName']);
                $image_url = $uploade_path.$image_full_name;
                $file->move($uploade_path,$image_full_name);
                $image[] = $image_url;
                ChildSubCategoryPhotos::create([
                    'name' => $image_full_name,
                    'path' => $uploade_path,
                    'child_sub_category_id' => $child_sub_category_photos->id,
                    'updated_at' => now(),
                    'created_at' => now(),
                ]);
            }
        }

        return response()->json(['ChildSubCategory'=>ChildSubCategory::with(['ChildsubCategoryImages','products'])->get()]);
    }

    /**
    * @OA\Post(
    *     path="/api/add/photos/for_childSubCategory",
    *     summary="Request that add photos child sub category",
    *     description="",
    *     tags={"Child Sub Category Section"},
    *     @OA\Parameter(
    *        name="child_sub_category_id",
    *        in="query",
    *        description="Please write child sub category id",
    *        required=true,
    *        allowEmptyValue=true,
    *     ),
    *     @OA\Response(
    *        response=200,
    *        description="Successful response",
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
    *         description="Validation error"
    *     )
    *   ),
    */
    public function addPhotosByNameOfChildSubCategory(Request $request)
    {
        $rules = [
            'child_sub_category_id' => 'required',
            // 'image.*' => 'required',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
        }
        $image = array();
        $childSubCategory = ChildSubCategory::where('id',$request->child_sub_category_id)->first();
        if($file = $request->file('image')){
            foreach($file as $file){
                $image_name = md5(rand(1000,10000));
                $ext = strtolower($file->getClientOriginalExtension());
                $image_full_name = $image_name.'.'.$ext;
                if (!File::exists('Child_sub_category_images'.'/'.$childSubCategory['photoFileName'])) {
                    File::makeDirectory('Child_sub_category_images'.'/'.$childSubCategory['photoFileName']);
                }
                $uploade_path = public_path('Child_sub_category_images'.'/'.$childSubCategory['photoFileName']);
                $image_url = $uploade_path.$image_full_name;
                $file->move($uploade_path,$image_full_name);
                $image[] = $image_url;
                ChildSubCategoryPhotos::create([
                    'name' => $image_full_name,
                    'path' => $uploade_path,
                    'child_sub_category_id' => $request->child_sub_category_id,
                    'updated_at' => now(),
                    'created_at' => now(),
                ]);
            }
        }

        return response()->json(['ChildSubCategory'=>ChildSubCategory::where('id',$request->child_sub_category_id)->with(['ChildsubCategoryImages','products'])->get()]);
    }

    /**
    * @OA\Post(
    *     path="/api/delete/childSubCategory/photo",
    *     summary="Request that delete photo child sub category",
    *     description="",
    *     tags={"Child Sub Category Section"},
    *     @OA\Parameter(
    *        name="child_sub_category_id",
    *        in="query",
    *        description="Please write child sub category id",
    *        required=true,
    *        allowEmptyValue=true,
    *     ),
    *     @OA\Parameter(
    *        name="sub_cat_poto_name",
    *        in="query",
    *        description="Please write photo name",
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
    *         description="Validation error"
    *     )
    *   ),
    */
    public function deleteChildSubCategoryPhoto(Request $request)
    {
        $rules = [
            'child_sub_category_id' => 'required',
            'sub_cat_poto_name' => 'required',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
        }
        $childSubCategory = ChildSubCategory::where('id',$request->child_sub_category_id)->first();
        $photo = ChildSubCategoryPhotos::where('child_sub_category_id',$childSubCategory->id)->where('name',$request['sub_cat_poto_name'])->first();
        unlink($photo['path'].'/'.$photo['name']);
        $photo->delete();

        return response()->json(['ChildSubCategory'=>ChildSubCategory::where('id',$request->child_sub_category_id)->with(['ChildsubCategoryImages','products'])->get()]);
    }

    /**
    * @OA\Post(
    *     path="/api/update-Child-SubCategory",
    *     summary="Request that update child sub category",
    *     description="",
    *     tags={"Child Sub Category Section"},
    *     @OA\Parameter(
    *        name="title",
    *        in="query",
    *        description="Please write title",
    *        required=true,
    *        allowEmptyValue=true,
    *     ),
    *     @OA\Parameter(
    *        name="category_id",
    *        in="query",
    *        description="Please write child category id",
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
    *         description="Validation error"
    *     )
    *   ),
    */
    public function updateChildSubCategory(Request $request)
    {
        $rules = [
            'title'=>'required',
            'category_id' => 'required',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
        }
        ChildSubCategory::where('id',$request['category_id'])->update([
            'title' => $request['title']
        ]);

        return response()->json(['ChildSubCategory'=>ChildSubCategory::where('id',$request['category_id'])->with(['ChildsubCategoryImages','products'])->get()]);
    }
}
