<?php

namespace App\Http\Controllers\Web\V1;

use App\Models\BigStorePhotos;
use App\Models\BigStores;
use Illuminate\Support\Facades\Validator;
use App\Models\Products;
use App\Models\Options;
use App\Models\Photos;
use App\Models\OptionPhotos;
use App\Models\Category;
use App\Models\SubCategory;
use App\Models\pivot_categories_products;
use App\Models\pivot_sub_categories_products;
use App\Models\CategoryPhotos;
use App\Models\SubCategoryPhotos;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use App\Http\Services\VarableServices;
use Illuminate\Routing\Controller as BaseController;

class AdminGlobalDeleteSectionController extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;
    
    public function __construct(
        public VarableServices $varableServices,
    ) {
       
    }

    public function deleteSubCategoryPhoto(Request $request)
    {
        $rules = [
            'sub_category_id' => 'required',
            'sub_cat_poto_name' => 'required',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
        }
        $subCategory = SubCategory::where('id',$request->sub_category_id)->first();
        $photo = SubCategoryPhotos::where('sub_category_id',$subCategory->id)->where('name',$request['sub_cat_poto_name'])->first();
        unlink($photo['path'].'/'.$photo['name']);
        $photo->delete();

        return response()->json(['deleted'=>true]);
    }

    public function deleteCategoryPhoto(Request $request)
    {
        $rules = [
            'category_id' => 'required',
            'cat_poto_name' => 'required',
        ];
       
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
        }
        $category = Category::where('id',$request->category_id)->first();
        $photo = CategoryPhotos::where('category_id',$category->id)->where('name',$request['cat_poto_name'])->first();
        unlink($photo['path'].'/'.$photo['name']);
        $photo->delete();

        return response()->json(['deleted'=>true]);
    }

    public function deleteBigstorePhoto(Request $request)
    {
        $rules = [
            'big_store_id' => 'required',
            'cat_poto_name' => 'required',
        ];
       
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
        }
        $bigStore = BigStores::where('id',$request->big_store_id)->first();
        $photo = BigStorePhotos::where('big_store_id',$bigStore->id)->where('name',$request['cat_poto_name'])->first();
        unlink($photo['path'].'/'.$photo['name']);
        $photo->delete();

        return response()->json(['deleted'=>true]);
    }

    public function deleteOptionPhoto(Request $request)
    {
        $rules = [
            'option_id' => 'required',
            'option_poto_name' => 'required',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
        }
        $option = Options::where('id',$request->option_id)->first();
        $photo = OptionPhotos::where('option_id',$option->id)->where('name',$request['option_poto_name'])->first();
        unlink($photo['path'].'/'.$photo['name']);
        $photo->delete();

        return response()->json(['deleted'=>true]);
    }

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
        $categoryPivot = pivot_sub_categories_products::where('sub_category_id',$request['sub_category_id'])->update([
            'sub_category_id' => $subCategoryProduct->id,
        ]);
        $category = SubCategory::where('id',$request['sub_category_id'])->delete();
           
        return response()->json(['deleted'=>true]);
    }

    public function deleteCategory(Request $request)
    {
        $rules = [
            'category_id' => 'required'
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
        }
        $categoryProduct = Category::where('status','001')->first();
        $categoryPivot = pivot_categories_products::where('category_id',$request['category_id'])->update([
            'category_id' => $categoryProduct->id,
        ]);
        $categoryPivot = pivot_sub_categories_products::where('category_id',$request['category_id'])->update([
            'category_id' => $categoryProduct->id,
        ]);
        $category = Category::where('id',$request['category_id'])->delete();
           
        return response()->json(['deleted'=>true]);
    }

    public function deletePhoto(Request $request)
    {
        $rules = [
            'prod_id' => 'required',
            'prod_numb' => 'required',
            'photo_name' => 'required',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
        }
        $product = Products::where('id',$request->prod_id)->where('productNumber',$request->prod_numb)->first();
        $photo = Photos::where('product_id',$product->id)->where('name',$request['photo_name'])->first();
        unlink($photo['path'].'/'.$photo['name']);
        $photo->delete();

        return response()->json(['deleted'=>true]);
    }

    public function deleteProductByID(Request $request)
    {
        $rules = [
            'product_id' => 'required',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
        }

        $product = Products::where('id',$request->product_id)->first();
        $optionOfProduct = Options::where('product_id',$request->product_id)->first();
        if ($product == null) {
            return response()->json(['product'=>'No data found']);
        }
        File::deleteDirectory(public_path('Images'.'/'.$product['photoFileName']));
        File::deleteDirectory(public_path('Option_Images'.'/'.$optionOfProduct['photoFileName']));

        $product->delete();

       
        
        return view('admin.dashboard')->with($this->varableServices->getdashboardVarables());
    }
}
