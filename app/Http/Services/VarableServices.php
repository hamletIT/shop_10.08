<?php

namespace App\Http\Services;

use App\Models\BigStores;
use App\Models\User;
use App\Models\Carts;
use App\Models\Products;
use App\Models\Stores;
use App\Models\Options;
use App\Models\Applications;
use App\Models\Category;
use App\Models\SubCategory;
use App\Models\ChildSubCategory;
use Carbon\Carbon;

class VarableServices
{
    public function getAllTypeOfCategories()
    {
        $allBigStores = BigStores::with(['categories','bigStoreImages'])->get();
        $allStores = Stores::get();
        $allCategory = Category::get();
        $allSubCategory = SubCategory::get();
        $allChildSubCategory = ChildSubCategory::get();
        $singleCategory = Category::where('status',001)->first();

        return compact('allStores','allCategory','allSubCategory','singleCategory','allChildSubCategory','allBigStores');
    }

    public function getUsersProductsStoresOptions()
    {
        $allUsers = User::get();
        $allStores = Stores::get();
        $allProducts = Products::get();

        return compact('allStores','allProducts','allUsers');
    }

    public function getdashboardVarables()
    {
        $allBigStores = BigStores::with(['categories','bigStoreImages'])->get();
        $application = Applications::get();
        $allUsers = User::with('userStore')->get();
        $allProducts = Products::with('store')->get();
        $allStores = Stores::get();
        $alloptions = Options::with(['product','optionImages'])->get();
        $allCategory = Category::with(['categoryImages','products'])->get();
        $singleCategory = Category::where('status','001')->with('products')->first();
        $allSubCategory = SubCategory::with('subCategoryImages')->with('products')->get();
        $allChildSubCategory = ChildSubCategory::with('ChildsubCategoryImages')->with('products')->get();

        return compact('allBigStores','singleCategory','allUsers','allProducts','allStores','alloptions','application','allCategory','allSubCategory','allChildSubCategory');
    }

    /**
     * @param string $table_name
     * @param ?string $request_name
     */
    public function getFilterResponse($request_name, $table_name)
    {
        if (isset($table_name) && $table_name == 'Product') {
            $application = Applications::get();
            $productFilter = Products::Where('name', 'LIKE', '%'.$request_name.'%')->with('store')->get();
            $allUsers = User::with('userStore')->get();
            $allProducts = Products::with('store')->get();
            $allStores = Stores::get();
            $alloptions = Options::with('product')->get();
            $allCategory = Category::get();
            $allSubCategory = SubCategory::with('subCategoryImages')->with('products')->get();

            return compact('allSubCategory','allUsers','allProducts','allStores','alloptions','productFilter','application','allCategory');
        }

        if (isset($table_name) && $table_name == 'Option') {
            $application = Applications::get();
            $optionFilter = Options::where('name', 'LIKE', '%'.$request_name.'%')->with('product')->get();
            $allUsers = User::with('userStore')->get();
            $allProducts = Products::with('store')->get();
            $allStores = Stores::get();
            $alloptions = Options::with('product')->get();
            $allCategory = Category::get();
            $allSubCategory = SubCategory::with('subCategoryImages')->with('products')->get();

            return compact('allSubCategory','allUsers','allProducts','allStores','alloptions','optionFilter','application','allCategory');
        }

        if (isset($table_name) && $table_name == 'Store') {
            $application = Applications::get();
            $storeFilter = Stores::where('name', 'LIKE', '%'.$request_name.'%')->get();
            $allUsers = User::with('userStore')->get();
            $allProducts = Products::with('store')->get();
            $allStores = Stores::get();
            $alloptions = Options::with('product')->get();
            $allCategory = Category::get();
            $allSubCategory = SubCategory::with('subCategoryImages')->with('products')->get();

            return compact('allSubCategory','allUsers','allProducts','allStores','alloptions','storeFilter','application','allCategory');
        }
        
        if (isset($table_name) && $table_name == 'Category') {
            $application = Applications::get();
            $categoryFilter = Category::where('title', 'LIKE', '%'.$request_name.'%')->get();
            $allUsers = User::with('userStore')->get();
            $allProducts = Products::with('store')->get();
            $allStores = Stores::get();
            $alloptions = Options::with('product')->get();
            $allCategory = Category::get();
            $allSubCategory = SubCategory::with('subCategoryImages')->with('products')->get();

            return compact('allSubCategory','allUsers','allProducts','allStores','alloptions','categoryFilter','application','allCategory');
        }

        if (isset($table_name) && $table_name == 'SubCategory') {
            $application = Applications::get();
            $subCategoryFilter = SubCategory::where('title', 'LIKE', '%'.$request_name.'%')->get();
            $allUsers = User::with('userStore')->get();
            $allProducts = Products::with('store')->get();
            $allStores = Stores::get();
            $alloptions = Options::with('product')->get();
            $allCategory = Category::get();
            $allSubCategory = SubCategory::with('subCategoryImages')->with('products')->get();

            return compact('allSubCategory','allUsers','allProducts','allStores','alloptions','subCategoryFilter','application','allCategory');
        }

        if (isset($table_name) && $table_name == 'ChildSubCategory') {
            $application = Applications::get();
            $childSubCategoryFilter = ChildSubCategory::where('title', 'LIKE', '%'.$request_name.'%')->get();
            $allUsers = User::with('userStore')->get();
            $allProducts = Products::with('store')->get();
            $allStores = Stores::get();
            $alloptions = Options::with('product')->get();
            $allCategory = Category::get();
            $allSubCategory = SubCategory::with('subCategoryImages')->with('products')->get();

            return compact('allSubCategory','allUsers','allProducts','allStores','alloptions','childSubCategoryFilter','application','allCategory');
        }
    }

    public function getStatisticSOfYear()
    {
        $application = Applications::get();
        $cartTotal = Carts::get();
        $storeTotal = Stores::get();
        $allUsersUn = User::where('status',0)->with('userStore')->get();
        $allUsersAn = User::where('status',1)->with('userStore')->get();
        $users = User::select('name', 'created_at')
        ->get()
        ->groupBy(function($date) {
            return Carbon::parse($date->created_at)->format('m'); 
        });
        $products = Products::select('name', 'created_at')
        ->get()
        ->groupBy(function($date) {
            return Carbon::parse($date->created_at)->format('m'); 
        });
        $category = Category::select('title', 'created_at')
        ->get()
        ->groupBy(function($date) {
            return Carbon::parse($date->created_at)->format('m'); 
        });
        $subCategory = Category::select('title', 'created_at')
        ->get()
        ->groupBy(function($date) {
            return Carbon::parse($date->created_at)->format('m'); 
        });

        $month1 = 0;$month2 = 0;$month3 = 0;$month4 = 0;$month5 = 0;$month6 = 0;$month7 = 0;$month8 = 0;$month9 = 0;$month10 = 0;$month11 = 0;$month12 = 0;
        $monthCat1 = 0;$monthCat2 = 0;$monthCat3 = 0;$monthCat4 = 0;$monthCat5 = 0;$monthCat6 = 0;$monthCat7 = 0;$monthCat8 = 0;$monthCat9 = 0;$monthCat10 = 0;$monthCat11 = 0;$monthCat12 = 0;
        $monthSubCat1 = 0;$monthSubCat2 = 0;$monthSubCat3 = 0;$monthSubCat4 = 0;$monthSubCat5 = 0;$monthSubCat6 = 0;$monthSubCat7 = 0;$monthSubCat8 = 0;$monthSubCat9 = 0;$monthSubCat10 = 0;$monthSubCat11 = 0;$monthSubCat12 = 0;
        $monthProd1= 0;$monthProd2= 0;$monthProd3= 0;$monthProd4= 0;$monthProd5= 0;$monthProd6= 0;$monthProd7= 0;$monthProd8= 0;$monthProd9= 0;$monthProd10 = 0;$monthProd11 = 0;$monthProd12 = 0;
        
        if (isset($users['01'])) {
            $month1 = count($users['01']);
        }
        if (isset($users['02'])) {
            $month2 = count($users['02']);
        }
        if (isset($users['03'])) {
            $month3 = count($users['03']);
        }
        if (isset($users['04'])) {
            $month4 = count($users['04']);
        }
        if (isset($users['05'])) {
            $month5 = count($users['05']);
        }
        if (isset($users['06'])) {
            $month6 = count($users['06']);
        }
        if (isset($users['07'])) {
            $month7 = count($users['07']);
        }
        if (isset($users['08'])) {
            $month8 = count($users['08']);
        }
        if (isset($users['09'])) {
            $month9 = count($users['09']);
        }
        if (isset($users['10'])) {
            $month10 = count($users['10']);
        }
        if (isset($users['11'])) {
            $month11 = count($users['11']);
        }
        if (isset($users['12'])) {
            $month12 = count($users['12']);
        }
        // -- prod section
        if (isset($products['01'])) {
            $monthProd1 = count($products['01']);
        }
        if (isset($products['02'])) {
            $monthProd2 = count($products['02']);
        }
        if (isset($products['03'])) {
            $monthProd3 = count($products['03']);
        }
        if (isset($products['04'])) {
            $monthProd4 = count($products['04']);
        }
        if (isset($products['05'])) {
            $monthProd5 = count($products['05']);
        }
        if (isset($products['06'])) {
            $monthProd6 = count($products['06']);
        }
        if (isset($products['07'])) {
            $monthProd7 = count($products['07']);
        }
        if (isset($products['08'])) {
            $monthProd8 = count($products['08']);
        }
        if (isset($products['09'])) {
            $monthProd9 = count($products['09']);
        }
        if (isset($products['10'])) {
            $monthProd10 = count($products['10']);
        }
        if (isset($products['11'])) {
            $monthProd11 = count($products['11']);
        }
        if (isset($products['12'])) {
            $monthProd12 = count($products['12']);
        }
        // -- category section 
        if (isset($category['01'])) {
            $monthCat1 = count($category['01']);
        }
        if (isset($category['02'])) {
            $monthCat2 = count($category['02']);
        }
        if (isset($category['03'])) {
            $monthCat3 = count($category['03']);
        }
        if (isset($category['04'])) {
            $monthCat4 = count($category['04']);
        }
        if (isset($category['05'])) {
            $monthCat5 = count($category['05']);
        }
        if (isset($category['06'])) {
            $monthCat6 = count($category['06']);
        }
        if (isset($category['07'])) {
            $monthCat7 = count($category['07']);
        }
        if (isset($category['08'])) {
            $monthCat8 = count($category['08']);
        }
        if (isset($category['09'])) {
            $monthCat9 = count($category['09']);
        }
        if (isset($category['10'])) {
            $monthCat10 = count($category['10']);
        }
        if (isset($category['11'])) {
            $monthCat11 = count($category['11']);
        }
        if (isset($users['12'])) {
            $monthCat12 = count($users['12']);
        }
        // -- sub category section 
        if (isset($subCategory['01'])) {
            $monthSubCat1 = count($subCategory['01']);
        }
        if (isset($subCategory['02'])) {
            $monthSubCat2 = count($subCategory['02']);
        }
        if (isset($subCategory['03'])) {
            $monthSubCat3 = count($subCategory['03']);
        }
        if (isset($subCategory['04'])) {
            $monthSubCat4 = count($subCategory['04']);
        }
        if (isset($subCategory['05'])) {
            $monthSubCat5 = count($subCategory['05']);
        }
        if (isset($subCategory['06'])) {
            $monthSubCat6 = count($subCategory['06']);
        }
        if (isset($subCategory['07'])) {
            $monthSubCat7 = count($subCategory['07']);
        }
        if (isset($subCategory['08'])) {
            $monthSubCat8 = count($subCategory['08']);
        }
        if (isset($subCategory['09'])) {
            $monthSubCat9 = count($subCategory['09']);
        }
        if (isset($subCategory['10'])) {
            $monthSubCat10 = count($subCategory['10']);
        }
        if (isset($subCategory['11'])) {
            $monthSubCat11 = count($subCategory['11']);
        }
        if (isset($subCategory['12'])) {
            $monthSubCat12 = count($subCategory['12']);
        }
        $allProducts = Products::with('store')->get();
        $allStores = Stores::get();
        $alloptions = Options::with('product')->get();

        return compact(
            'storeTotal',
            'cartTotal',
            'monthProd1',
            'monthProd2',
            'monthProd3',
            'monthProd4',
            'monthProd5',
            'monthProd6',
            'monthProd7',
            'monthProd8',
            'monthProd9',
            'monthProd10',
            'monthProd11',
            'monthProd12',
            'month1',
            'month2',
            'month3',
            'month4',
            'month5',
            'month6',
            'month7',
            'month8',
            'month9',
            'month10',
            'month11',
            'month12',
            'monthCat1',
            'monthCat2',
            'monthCat3',
            'monthCat4',
            'monthCat5',
            'monthCat6',
            'monthCat7',
            'monthCat8',
            'monthCat9',
            'monthCat10',
            'monthCat11',
            'monthCat12',
            'monthSubCat1',
            'monthSubCat2',
            'monthSubCat3',
            'monthSubCat4',
            'monthSubCat5',
            'monthSubCat6',
            'monthSubCat7',
            'monthSubCat8',
            'monthSubCat9',
            'monthSubCat10',
            'monthSubCat11',
            'monthSubCat12',
            'allUsersUn',
            'allUsersAn',
            'allProducts',
            'allStores',
            'alloptions',
            'application',
        );
    }
}