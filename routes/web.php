<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\V1\AdminGlobalController;
use App\Http\Controllers\Web\V1\AdminGlobalDeleteSectionController;
use App\Http\Controllers\Web\V1\AdminGlobalCreateOrAddSectionController;
use App\Http\Controllers\Web\V1\AdminGlobalUpdateSectionController;
use App\Http\Controllers\Web\V1\AdminGlobalShowSectionController;
use App\Http\Controllers\Web\User\FacebookController;
use App\Http\Controllers\Web\User\GoogleController;
use App\Http\Controllers\Web\User\AuthUserController;
use App\Http\Controllers\Web\Admin\AuthAdminController;
use App\Http\Controllers\Web\Admin\AdminProductsController;
use App\Http\Controllers\Web\User\CartUserController;
use App\Http\Controllers\Web\User\CheckoutController;
use App\Http\Controllers\Web\User\ReviewController;
use App\Http\Controllers\Web\User\RatingController;









use App\Http\Controllers\LangController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });
Route::get('/', function () {
    return view('user.login.login');
});

Route::get('/admin', function () {
    return view('admin.login.login');
});
Route::prefix('user')->group(function() {
    Route::get('facebook/login',[FacebookController::class,'provider'])->name('facebook.login');
    Route::get('facebook/callback',[FacebookController::class,'handleCallback'])->name('facebok.callback');
    Route::get('google/login',[GoogleController::class,'provider'])->name('google.login');
    Route::get('google/login/callback',[GoogleController::class,'callbackHandle'])->name('google.login.callback');

    Route::post('login',[AuthUserController::class,'login'])->name('user.login');
    Route::get('register/show',[AuthUserController::class,'registerShow'])->name('user.register.show');
    Route::get('login/show',[AuthUserController::class,'loginShow'])->name('user.login.show');
    Route::post('register',[AuthUserController::class,'register'])->name('user.register');
    Route::get('/dashboard',[AuthUserController::class,'dashboard'])->name('user.dashboard');

    Route::post('add-to/cart',[CartUserController::class,'addToCart'])->name('add.cart');
    Route::get('show/cart',[CartUserController::class,'showCart'])->name('user.cart');
    Route::post('add/quantity/for-one/product',[CartUserController::class,'addQuantityForOneProduct'])->name('add.prod.qty.cart');
    Route::post('/delete/cart/products',[CartUserController::class,'deleteCartProducts'])->name('delete.cart.prod');;
    Route::get('show/checkoute',[CartUserController::class,'showChekoutPage'])->name('user.checkout');
    Route::post('save/checkoute',[CheckoutController::class,'checkout'])->name('user.checkout.save');
    Route::get('/cancel',[CheckoutController::class,'cancelCheckout'])->name('checkout.cancel');
    Route::get('/success',[CheckoutController::class,'successCheckout'])->name('checkout.success');
    Route::get('/show/orders',[CheckoutController::class,'showOrders'])->name('show.orders');

    Route::get('/rating/{productID}',[RatingController::class,'addRatingToProduct'])->name('add.rating');
    Route::get('/review/{productID}',[ReviewController::class,'addReviewToProduct'])->name('add.review');

    Route::post('/review/save',[ReviewController::class,'saveReviewToProduct'])->name('set.review.prod');
    Route::post('/rating/save',[RatingController::class,'saveRatingToProduct'])->name('set.rating.prod');
    
});


Route::prefix('admin')->group(function() {

    Route::post('login',[AuthAdminController::class,'login'])->name('admin.login');
    Route::post('register',[AuthAdminController::class,'register'])->name('admin.register');
    Route::get('register/show',[AuthAdminController::class,'registerShow'])->name('admin.register.show');
    Route::get('login/show',[AuthAdminController::class,'loginShow'])->name('admin.login.show');
    Route::get('/dashboard',[AuthAdminController::class,'dashboard'])->name('admin.dashboard');

    Route::post('/add-Product',[AdminProductsController::class,'addProduct'])->name('product.submit');
    Route::get('/show/products',[AdminProductsController::class,'showProducts'])->name('admin.products');
    Route::get('/show-product-update/{productID}',[AdminProductsController::class,'showPrductUpdate'])->name('admin.update.show');
    Route::post('/update-product-filds',[AdminProductsController::class,'updateProductFilds'])->name('admin.update.product');
    Route::post('/delete/product/by/ID',[AdminProductsController::class,'deleteProductByID'])->name('delete.product');
    Route::get('/show/orders',[AdminProductsController::class,'showAllOrders'])->name('admin.show.all.orders');
    Route::get('/show/users',[AdminProductsController::class,'showAllUsers'])->name('admin.show.all.users');

   

    // // -----------------------------------CREATE OR ADD SECTION-----------------------------------------------------------------\\
    Route::post('/add/photos/for_childSubCategory',[AdminGlobalCreateOrAddSectionController::class,'addPhotosByNameOfChildSubCategory'])->name('add.child_sub_category_photos');
    Route::post('/set/banner/childSubCategory/photo',[AdminGlobalCreateOrAddSectionController::class,'setBannerChildSubCategoryPhoto'])->name('set.childSubCategory.banner');
    Route::post('/add/photos/for_subCategory',[AdminGlobalCreateOrAddSectionController::class,'addPhotosByNameOfSubCategory'])->name('add.sub_category_photos');
    Route::post('/set/banner/subCategory/photo',[AdminGlobalCreateOrAddSectionController::class,'setBannerSubCategoryPhoto'])->name('set.subCategory.banner');
    Route::post('/add/photos/for_category',[AdminGlobalCreateOrAddSectionController::class,'addPhotosByNameOfCategory'])->name('add.category_photos');
    Route::post('/add/photos/for_BigStore',[AdminGlobalCreateOrAddSectionController::class,'addPhotosByNameOfBigStore'])->name('add.bigStore_photos');
    Route::post('/set/banner/category/photo',[AdminGlobalCreateOrAddSectionController::class,'setBannerCategoryPhoto'])->name('set.category.banner');
    Route::post('/set/banner/bigStore/photo',[AdminGlobalCreateOrAddSectionController::class,'setBannerBigStorePhoto'])->name('set.bigStore.banner');
    Route::post('/add-ChildSubCategory',[AdminGlobalCreateOrAddSectionController::class,'addChildSubCategory'])->name('ChildsubCategory.submit');
    Route::post('/add/photos/for_option',[AdminGlobalCreateOrAddSectionController::class,'addPhotosByNameOfOption'])->name('add.option_photos');
    Route::post('/add/photos/for_product',[AdminGlobalCreateOrAddSectionController::class,'addPhotosByNameOfProducts'])->name('add.photos');
    Route::post('/add-SubCategory',[AdminGlobalCreateOrAddSectionController::class,'addSubCategory'])->name('subCategory.submit');
    Route::post('/set/banner/photo',[AdminGlobalCreateOrAddSectionController::class,'setBannerPhoto'])->name('set.banner');
    Route::post('/add-Big-Store',[AdminGlobalCreateOrAddSectionController::class,'addBigStore'])->name('bigStore.submit');
    Route::post('/add-Category',[AdminGlobalCreateOrAddSectionController::class,'addCategory'])->name('category.submit');
    // Route::post('/add-Product',[AdminGlobalCreateOrAddSectionController::class,'addProduct'])->name('product.submit');
    Route::post('/add-Option',[AdminGlobalCreateOrAddSectionController::class,'addOption'])->name('option.submit');
    Route::post('/add-Store',[AdminGlobalCreateOrAddSectionController::class,'addStore'])->name('store.submit');

    // -----------------------------------UPDATE SECTION-----------------------------------------------------------------\\
    Route::post('/update-Child-SubCategory',[AdminGlobalUpdateSectionController::class,'updateChildSubCategory'])->name('childSubCategory.update');
    // Route::post('/update-product-filds',[AdminGlobalUpdateSectionController::class,'updateProductFilds'])->name('admin.update.product');
    Route::post('/update-SubCategory',[AdminGlobalUpdateSectionController::class,'updateSubCategory'])->name('subCategory.update');
    Route::post('/update-Category',[AdminGlobalUpdateSectionController::class,'updateCategory'])->name('category.update');
    
    // -----------------------------------DELETE SECTION-----------------------------------------------------------------\\
    Route::post('/delete/childSubCategory/photo',[AdminGlobalDeleteSectionController::class,'deleteChildSubCategoryPhoto'])->name('delete.childSubCategory.photo');
    Route::post('/delete/subCategory/photo',[AdminGlobalDeleteSectionController::class,'deleteSubCategoryPhoto'])->name('delete.subCategory.photo');
    Route::post('/delete/category/photo',[AdminGlobalDeleteSectionController::class,'deleteCategoryPhoto'])->name('delete.category.photo');
    Route::post('/delete/bigstore/photo',[AdminGlobalDeleteSectionController::class,'deleteBigstorePhoto'])->name('delete.bigstore.photo');
    Route::post('/delete/option/photo',[AdminGlobalDeleteSectionController::class,'deleteOptionPhoto'])->name('delete.option.photo');
    Route::post('/delete/sub/category',[AdminGlobalDeleteSectionController::class,'deleteSubCategory'])->name('delete.subCategory');
    // Route::post('/delete/product/by/ID',[AdminGlobalDeleteSectionController::class,'deleteProductByID'])->name('delete.product');
    Route::post('/delete/category',[AdminGlobalDeleteSectionController::class,'deleteCategory'])->name('delete.category');  
    Route::post('/delete/photo',[AdminGlobalDeleteSectionController::class,'deletePhoto'])->name('delete.photo');

    // -----------------------------------SHOW SECTION-----------------------------------------------------------------\\
    Route::get('/show/child/sub-category',[AdminGlobalShowSectionController::class,'showChildSubCategory'])->name('admin.childsubCategory');
    // Route::get('/show-product-update/{productID}',[AdminGlobalShowSectionController::class,'showPrductUpdate'])->name('admin.update.show');
    Route::get('/show/sub-category',[AdminGlobalShowSectionController::class,'showSubCategory'])->name('admin.subCategory');
    Route::get('/show/statistics',[AdminGlobalShowSectionController::class,'showStatistic'])->name('admin.statistic');
    Route::get('/show/languages',[AdminGlobalShowSectionController::class,'showlanguages'])->name('admin.languages');
    Route::get('/show/BigStores',[AdminGlobalShowSectionController::class,'showBigStore'])->name('admin.Bigstore');
    Route::get('/show/products',[AdminGlobalShowSectionController::class,'showProducts'])->name('admin.products');
    Route::get('/show/category',[AdminGlobalShowSectionController::class,'showCategory'])->name('admin.category');
    Route::get('/show/options',[AdminGlobalShowSectionController::class,'showOption'])->name('admin.option');
    Route::get('/show/stores',[AdminGlobalShowSectionController::class,'showStore'])->name('admin.store');

    // -----------------------------------LANGUAGE SECTION-----------------------------------------------------------------\\
    Route::get('lang/change', [LangController::class, 'change'])->name('changeLang');
    Route::get('lang/home', [LangController::class, 'index']);

    // -----------------------------------GLOBAL SECTION-----------------------------------------------------------------\\
    Route::post('/filter',[AdminGlobalController::class,'filter'])->name('page.filter');
    Route::get('/get/applicat',[AdminGlobalController::class,'/getApplicat']);
});

