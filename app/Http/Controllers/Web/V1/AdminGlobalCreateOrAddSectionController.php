<?php

namespace App\Http\Controllers\Web\V1;

use Illuminate\Support\Facades\Validator;
use App\Models\BigStores;
use App\Models\BigStorePhotos;
use App\Models\User;
use App\Models\Products;
use App\Models\Stores;
use App\Models\Options;
use App\Models\Photos;
use App\Models\OptionPhotos;
use App\Models\Category;
use App\Models\SubCategory;
use App\Models\Prices;
use App\Models\pivot_categories_products;
use App\Models\pivot_sub_categories_products;
use App\Models\pivot_child_sub_categories;
use App\Models\CategoryPhotos;
use App\Models\SubCategoryPhotos;
use App\Models\ChildSubCategory;
use App\Models\ChildSubCategoryPhotos;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\File;
use App\Http\Services\VarableServices;
use Illuminate\Routing\Controller as BaseController;

class AdminGlobalCreateOrAddSectionController extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;
    
    public function __construct(
        public VarableServices $varableServices,
    ) {
       
    }

    public function addBigStore(Request $request)
    {
        $rules = [
            'name'=>'required|unique:big_stores,name',
            'info' => 'required',
            'photoFileName' => 'required|unique:big_stores,photoFileName',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return redirect()->back()->with(array('errors' => $validator->getMessageBag()->toArray()));
        }

        $bigStore = BigStores::insertGetId([
            'name' => $request['name'],
            'status' => 1,
            'info' => $request['info'],
            'photoFileName' => $request['photoFileName'],
            'photoFilePath' => 'test',
            'updated_at' => now(),
            'created_at' => now(),
        ]);
        $image = array();
        $bigStore_photos = BigStores::where('id',$bigStore)->first();
        if($file = $request->file('image')){
            foreach($file as $file){
                $image_name = md5(rand(1000,10000));
                $ext = strtolower($file->getClientOriginalExtension());
                $image_full_name = $image_name.'.'.$ext;
                if (!File::exists('Big_Store_images'.'/'.$bigStore_photos['photoFileName'])) {
                    File::makeDirectory('Big_Store_images'.'/'.$bigStore_photos['photoFileName']);
                }
                $uploade_path = public_path('Big_Store_images'.'/'.$bigStore_photos['photoFileName']);
                $image_url = $uploade_path.$image_full_name;
                $file->move($uploade_path,$image_full_name);
                $image[] = $image_url;
                BigStorePhotos::create([
                    'name' => $image_full_name,
                    'path' => $uploade_path,
                    'big_store_id' => $bigStore_photos->id,
                    'updated_at' => now(),
                    'created_at' => now(),
                ]);
            }
        }

        return redirect()->back();
    }

    public function addCategory(Request $request)
    {
        $rules = [
            'title'=>'required|unique:categories,title',
            'photoFileName' => 'required|unique:categories,photoFileName',
            'big_store_id' => 'required',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return redirect()->back()->with(array('errors' => $validator->getMessageBag()->toArray()));
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
        $allCategory = Category::get();

        return redirect()->back()->with(compact('allCategory'));
    }

    public function addSubCategory(Request $request)
    {
        $rules = [
            'title'=>'required|unique:sub_categories,title',
            'photoFileName' => 'required|unique:sub_categories,photoFileName',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return redirect()->back()->with(array('errors' => $validator->getMessageBag()->toArray()));
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
        $allSubCategory = SubCategory::get();
        $allCategory = Category::get();

        return redirect()->back()->with(compact('allSubCategory','allCategory'));
    }

    public function addChildSubCategory(Request $request)
    {
        $rules = [
            'title'=>'required|unique:sub_categories,title',
            'photoFileName' => 'required|unique:sub_categories,photoFileName',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return redirect()->back()->with(array('errors' => $validator->getMessageBag()->toArray()));
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

        return redirect()->back()->with($this->varableServices->getAllTypeOfCategories());
    }

    public function addStore(Request $request)
    {
        $rules = [
            'name' => 'required',
            'status' => 'required',
            'info' => 'required',
            'user_id' => 'required',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return redirect()->back()->with(array('errors' => $validator->getMessageBag()->toArray()));
        }
        $store = Stores::insertGetId([
            'name' => $request['name'],
            'status' => $request['status'],
            'info' => $request['info'],
            'user_id' => $request['user_id'],
            'updated_at' => now(),
            'created_at' => now(),
        ]);
        $allUsers = User::get();

        return redirect()->back()->with(compact('allUsers'));
    }

    public function addProduct(Request $request)
    {
        $rules = [
            'name' => 'required',
            'type' => 'required',
            'description' => 'required',
            'photoFileName' => 'required',
            'image' => 'required',
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
            return redirect()->back()->with(array('errors' => $validator->getMessageBag()->toArray()));
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

        return redirect()->back()->with($this->varableServices->getAllTypeOfCategories());
    }

    public function addOption(Request $request)
    {
        // dd($request->all());
        $rules = [
            'product_id' => 'required|numeric|integer',
            'name' => 'required',
            'information' => 'required',
            'status' => 'required',
            'price' => 'required',
            'image' => 'required',
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
        $allProducts = Products::get();

        return redirect()->back()->with(compact('allProducts'));
    }

    public function addPhotosByNameOfProducts(Request $request)
    {
        $rules = [
            'prod_id' => 'required',
            'image.*' => 'required',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
        }
        $image = array();
        $product = Products::where('id',$request->prod_id)->first();
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
                    'product_id' => $request->prod_id,
                ]);
            }
        }

        return redirect()->back();
    }

    public function addPhotosByNameOfOption(Request $request)
    {
        $rules = [
            'option_id' => 'required',
            'image.*' => 'required',
        ];
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
        }
        $image = array();
        $option = Options::where('id',$request->option_id)->first();
        if($file = $request->file('image')){
            foreach($file as $file){
                $image_name = md5(rand(1000,10000));
                $ext = strtolower($file->getClientOriginalExtension());
                $image_full_name = $image_name.'.'.$ext;
                if (!File::exists('Option_Images'.'/'.$option['photoFileName'])) {
                    File::makeDirectory('Option_Images'.'/'.$option['photoFileName']);
                }
                $uploade_path = public_path('Option_Images'.'/'.$option['photoFileName']);
                $image_url = $uploade_path.$image_full_name;
                $file->move($uploade_path,$image_full_name);
                $image[] = $image_url;
                OptionPhotos::create([
                    'name' => $image_full_name,
                    'path' => $uploade_path,
                    'option_id' => $request->option_id,
                    'updated_at' => now(),
                    'created_at' => now(),
                ]);
            }
        }

        return redirect()->back();
    }

    public function addPhotosByNameOfCategory(Request $request)
    {
        $rules = [
            'category_id' => 'required',
            'image.*' => 'required',
        ];
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
        }
        $image = array();
        $category = Category::where('id',$request->category_id)->first();
        if($file = $request->file('image')){
            foreach($file as $file){
                $image_name = md5(rand(1000,10000));
                $ext = strtolower($file->getClientOriginalExtension());
                $image_full_name = $image_name.'.'.$ext;
                if (!File::exists('Category_images'.'/'.$category['photoFileName'])) {
                    File::makeDirectory('Category_images'.'/'.$category['photoFileName']);
                }
                $uploade_path = public_path('Category_images'.'/'.$category['photoFileName']);
                $image_url = $uploade_path.$image_full_name;
                $file->move($uploade_path,$image_full_name);
                $image[] = $image_url;
                CategoryPhotos::create([
                    'name' => $image_full_name,
                    'path' => $uploade_path,
                    'category_id' => $request->category_id,
                    'updated_at' => now(),
                    'created_at' => now(),
                ]);
            }
        }

        return redirect()->back();
    }

    public function addPhotosByNameOfSubCategory(Request $request)
    {
        $rules = [
            'sub_category_id' => 'required',
            'image.*' => 'required',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
        }
        $image = array();
        $subCategory = SubCategory::where('id',$request->sub_category_id)->first();
        if($file = $request->file('image')){
            foreach($file as $file){
                $image_name = md5(rand(1000,10000));
                $ext = strtolower($file->getClientOriginalExtension());
                $image_full_name = $image_name.'.'.$ext;
                if (!File::exists('Sub_category_images'.'/'.$subCategory['photoFileName'])) {
                    File::makeDirectory('Sub_category_images'.'/'.$subCategory['photoFileName']);
                }
                $uploade_path = public_path('Sub_category_images'.'/'.$subCategory['photoFileName']);
                $image_url = $uploade_path.$image_full_name;
                $file->move($uploade_path,$image_full_name);
                $image[] = $image_url;
                SubCategoryPhotos::create([
                    'name' => $image_full_name,
                    'path' => $uploade_path,
                    'sub_category_id' => $request->sub_category_id,
                    'updated_at' => now(),
                    'created_at' => now(),
                ]);
            }
        }

        return redirect()->back();
    }

    public function setBannerPhoto(Request $request)
    {
        $rules = [
            'photo_id' => 'required',
            'banner' => 'required',
            'prod_id' => 'required',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return redirect()->back()->with(array('errors' => $validator->getMessageBag()->toArray()));
        }
        $getproductphotos = Photos::where('product_id',$request['prod_id'])->update([
            'banner' => null
        ]);
        $setBanner = Photos::where('id',$request['photo_id'])->update([
            'banner' => $request['banner']
        ]);

        return redirect()->back();
    }

    public function setBannerCategoryPhoto(Request $request)
    {
        $rules = [
            'photo_id' => 'required',
            'banner' => 'required',
            'category_id' => 'required',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return redirect()->back()->with(array('errors' => $validator->getMessageBag()->toArray()));
        }
        $getproductphotos = CategoryPhotos::where('category_id',$request['category_id'])->update([
            'banner' => null
        ]);
        $setBanner = CategoryPhotos::where('id',$request['photo_id'])->update([
            'banner' => $request['banner']
        ]);

        return redirect()->back();
    }

    public function setBannerSubCategoryPhoto(Request $request)
    {
        $rules = [
            'photo_id' => 'required',
            'banner' => 'required',
            'sub_category_id' => 'required',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return redirect()->back()->with(array('errors' => $validator->getMessageBag()->toArray()));
        }
        $getproductphotos = SubCategoryPhotos::where('sub_category_id',$request['sub_category_id'])->update([
            'banner' => null
        ]);
        $setBanner = SubCategoryPhotos::where('id',$request['photo_id'])->update([
            'banner' => $request['banner']
        ]);

        return redirect()->back();
    }
}
