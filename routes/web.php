<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\User\FacebookController;
use App\Http\Controllers\Web\User\GoogleController;
use App\Http\Controllers\Web\User\AuthUserController;
use App\Http\Controllers\Web\Admin\AuthAdminController;
use App\Http\Controllers\Web\Admin\AdminProductsController;
use App\Http\Controllers\Web\User\CartUserController;
use App\Http\Controllers\Web\User\CheckoutController;
use App\Http\Controllers\Web\User\ReviewController;
use App\Http\Controllers\Web\User\RatingController;
use App\Http\Controllers\Web\User\ProductController;
use App\Http\Controllers\Web\Admin\AdminOrdesUsersController;
use App\Http\Controllers\Web\Admin\CategoryController;


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

    Route::post('add/quantity/for-one/product',[CartUserController::class,'addQuantityForOneProduct'])->name('add.prod.qty.cart');
    Route::post('/delete/cart/products',[CartUserController::class,'deleteCartProducts'])->name('delete.cart.prod');
    Route::get('show/checkoute',[CartUserController::class,'showChekoutPage'])->name('user.checkout');
    Route::post('add-to/cart',[CartUserController::class,'addToCart'])->name('add.cart');
    Route::get('show/cart',[CartUserController::class,'showCart'])->name('user.cart');

    Route::get('facebook/callback',[FacebookController::class,'handleCallback'])->name('facebok.callback');
    Route::get('facebook/login',[FacebookController::class,'provider'])->name('facebook.login');

    Route::get('google/login/callback',[GoogleController::class,'callbackHandle'])->name('google.login.callback');
    Route::get('google/login',[GoogleController::class,'provider'])->name('google.login');

    Route::get('register/show',[AuthUserController::class,'registerShow'])->name('user.register.show');
    Route::get('login/show',[AuthUserController::class,'loginShow'])->name('user.login.show');
    Route::get('/dashboard',[AuthUserController::class,'dashboard'])->name('user.dashboard');
    Route::post('register',[AuthUserController::class,'register'])->name('user.register');
    Route::post('login',[AuthUserController::class,'login'])->name('user.login');

    Route::post('save/checkoute',[CheckoutController::class,'checkout'])->name('user.checkout.save');
    Route::get('/success',[CheckoutController::class,'successCheckout'])->name('checkout.success');
    Route::get('/cancel',[CheckoutController::class,'cancelCheckout'])->name('checkout.cancel');
    Route::get('/show/orders',[CheckoutController::class,'showOrders'])->name('show.orders');

    Route::get('/rating/{productID}',[RatingController::class,'addRatingToProduct'])->name('add.rating');
    Route::post('/rating/save',[RatingController::class,'saveRatingToProduct'])->name('set.rating.prod');

    Route::get('/review/{productID}',[ReviewController::class,'addReviewToProduct'])->name('add.review');
    Route::post('/review/save',[ReviewController::class,'saveReviewToProduct'])->name('set.review.prod');

    Route::get('/show/category',[CategoryController::class,'showCategory'])->name('show.category');
    
    Route::post('/filtered/product',[ProductController::class,'filterProduct'])->name('filter.product');

   
});


Route::prefix('admin')->group(function() {

    Route::get('register/show',[AuthAdminController::class,'registerShow'])->name('admin.register.show');
    Route::get('login/show',[AuthAdminController::class,'loginShow'])->name('admin.login.show');
    Route::get('/dashboard-public',[AuthAdminController::class,'dashboardPublic'])->name('admin.dashboard.public');
    Route::post('register',[AuthAdminController::class,'register'])->name('admin.register');
    Route::post('login',[AuthAdminController::class,'login'])->name('admin.login');

    Route::get('/show/orders',[AdminOrdesUsersController::class,'showAllOrders'])->name('admin.show.all.orders');
    Route::get('/show/users',[AdminOrdesUsersController::class,'showAllUsers'])->name('admin.show.all.users');
    Route::get('/dashboard',[AdminOrdesUsersController::class,'dashboard'])->name('admin.dashboard');

    Route::get('/show-product-update/{productID}',[AdminProductsController::class,'showPrductUpdate'])->name('admin.update.show');
    Route::post('/update-product-filds',[AdminProductsController::class,'updateProductFilds'])->name('admin.update.product');
    Route::post('/delete/product/by/ID',[AdminProductsController::class,'deleteProductByID'])->name('delete.product');
    Route::post('/filtered/product',[AdminProductsController::class,'filterProduct'])->name('filter.product.admin');
    Route::get('/show/products',[AdminProductsController::class,'showProducts'])->name('admin.products');
    Route::post('/add-Product',[AdminProductsController::class,'addProduct'])->name('product.submit');
});

