<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\ApiRegisterController;
use App\Http\Controllers\Api\V1\ApiProductController;
use App\Http\Controllers\Api\V1\ApiCartController;
use App\Http\Controllers\Api\V1\ApiIPController;
use App\Http\Controllers\Api\V1\ApiOptionController;
use App\Http\Controllers\Api\V1\ApiStoreController;
use App\Http\Controllers\Api\V1\ApiOrderController;
use App\Http\Controllers\Api\V1\ApiPaymentController;
use App\Http\Controllers\Api\V1\ApiApplicationsController;
use App\Http\Controllers\Api\V1\ApiCategoryController;
use App\Http\Controllers\Api\V1\ApiSubCategoryController;
use App\Http\Controllers\Api\V1\ApiBigStoreController;
use App\Http\Controllers\Api\V1\ApiChildSubCategoryController;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });


Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/register/sms',[ApiRegisterController::class,'registerBySMS']);
Route::post('/register/call',[ApiRegisterController::class,'registerByCall']);
Route::post('/accept/register/code',[ApiRegisterController::class,'acceptRegisterCode']);
Route::post('/login',[ApiRegisterController::class,'login']);
Route::post('/create/applications',[ApiApplicationsController::class,'createApplications']);
Route::get('/get/applications',[ApiApplicationsController::class,'getApplications']);
Route::get('/get/all/option',[ApiOptionController::class,'getOptions']);
Route::get('/get/option',[ApiOptionController::class,'getOption']);
Route::get('/get/store',[ApiStoreController::class,'getStore']);
Route::get('/get/all/store',[ApiStoreController::class,'getAllStores']);
Route::get('/get/all/orders',[ApiOrderController::class,'getAllOrders']);
Route::get('/get/order',[ApiOrderController::class,'getOrder']);
Route::get('/filter/order',[ApiOrderController::class,'filterOrder']);
Route::get('/get/bigStores',[ApiBigStoreController::class,'getBigStores']);
Route::get('/get/bigStore',[ApiBigStoreController::class,'getBigStore']);
Route::get('/get/child/sub/categories',[ApiChildSubCategoryController::class,'getChildSubCategories']);
Route::get('/get/child/sub/category',[ApiChildSubCategoryController::class,'getChildSubCategory']);


Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::post('/delete/applications',[ApiApplicationsController::class,'deleteByIDApplications']);

    Route::post('/logout', [ApiRegisterController::class, 'logout']);
    // cart section
    Route::post('/add/toCart',[ApiCartController::class,'addToCart']);
    Route::get('/get/cart/products',[ApiCartController::class,'getCartProducts']);
    Route::post('/delete/cart/products',[ApiCartController::class,'deleteCartProducts']);
    Route::get('/filter/cart',[ApiCartController::class,'filterCart']);

    // product section
    Route::post('/create/product',[ApiProductController::class,'createProduct']);
    Route::post('/update/product',[ApiProductController::class,'updateProduct']);
    Route::post('/delete/product',[ApiProductController::class,'deleteProduct']);
    Route::post('/delete/photo/product',[ApiProductController::class,'deletePhotoByNameOfProduct']);
    Route::post('/delete/photos/product',[ApiProductController::class,'deletePhotosProduct']);
    Route::post('/add/photo/product',[ApiProductController::class,'addPhotosByNameOfProduct']);
    
    // option section
    Route::post('/create/option',[ApiOptionController::class,'createOptionsForProduct']);

    // store section
    Route::post('/create/store',[ApiStoreController::class,'createStore']);
    Route::post('/delete/store',[ApiStoreController::class,'deleteStore']);

    // order section
    Route::post('/delete/order',[ApiOrderController::class,'deleteOrder']);
    Route::post('/create/order',[ApiOrderController::class,'createOrder']);

    // category section
    Route::post('/create/category',[ApiCategoryController::class,'createCategory']);
    Route::post('/update/category',[ApiCategoryController::class,'updateCategory']);
    Route::post('/delete/category',[ApiCategoryController::class,'deleteCategory']);

    // Sub category section
    Route::post('/create/sub/category',[ApiSubCategoryController::class,'createSubCategory']);
    Route::post('/update/sub/category',[ApiSubCategoryController::class,'updateSubCategory']);
    Route::post('/delete/sub/category',[ApiSubCategoryController::class,'deleteSubCategory']);

    // big store section
    Route::post('/add-Big-Store',[ApiBigStoreController::class,'addBigStore']);
    Route::post('/add/photos/for_BigStore',[ApiBigStoreController::class,'addPhotosByNameOfBigStore']);
    Route::post('/delete/bigstore/photo',[ApiBigStoreController::class,'deleteBigstorePhoto']);

    // child sub category section
    Route::post('/add-ChildSubCategory',[ApiChildSubCategoryController::class,'addChildSubCategory']);
    Route::post('/add/photos/for_childSubCategory',[ApiChildSubCategoryController::class,'addPhotosByNameOfChildSubCategory']);
    Route::post('/delete/childSubCategory/photo',[ApiChildSubCategoryController::class,'deleteChildSubCategoryPhoto']);
    Route::post('/update-Child-SubCategory',[ApiChildSubCategoryController::class,'updateChildSubCategory']);
});

Route::get('get/store/products',[ApiProductController::class,'getstoreProducts']);
Route::get('/filter/sub/catagory/byTitle',[ApiSubCategoryController::class,'filterSubCategoryByTitle']);
Route::get('/get/catagories',[ApiCategoryController::class,'getCategories']);
Route::get('/get/catagories/with/sub/catagories',[ApiCategoryController::class,'getCategoriesWithSubCategories']);
Route::get('/get/sub/catagories',[ApiSubCategoryController::class,'getSubCategories']);
Route::get('/get/all/stores/unAuth',[ApiStoreController::class,'getAllStoreUnAuth']);
Route::get('/get/single/store/unAuth',[ApiStoreController::class,'getSingleStoreUnAuth']);
Route::get('/get/product/list',[ApiProductController::class,'getProductList']);
Route::get('/filter/catagory/byTitle',[ApiCategoryController::class,'filterCategoryByTitle']);
Route::get('get/single/product/andphotos',[ApiProductController::class,'getSingleProductAndPhotos']);
Route::get('get/photosAnd/products',[ApiProductController::class,'getPhotosAndProducts']);
Route::get('get/single/photosAnd/products',[ApiProductController::class,'getSingleStorePhotosAndProducts']);
Route::get('/filter/product',[ApiProductController::class,'filterProduct']);
Route::get('/filter/option',[ApiOptionController::class,'filterOption']);
Route::get('/filter/store',[ApiStoreController::class,'filterStore']);

// payment section
Route::post('/create/payment',[ApiPaymentController::class,'createPayment']);
Route::get('/get/payment',[ApiPaymentController::class,'getPayment']);
Route::post('/accept/purchase',[ApiPaymentController::class,'acceptPurchase']);





















