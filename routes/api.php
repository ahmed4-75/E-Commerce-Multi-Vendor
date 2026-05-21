<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LoginNoPasswordController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\VerifyEmailController;
use App\Http\Controllers\CartsController;
use App\Http\Controllers\CategoriesController;
use App\Http\Controllers\CommentsController;
use App\Http\Controllers\OrdersController;
use App\Http\Controllers\ProductsController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ShopsController;
use App\Http\Controllers\TestController;
use App\Http\Controllers\TranslationsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/test',[TestController::class, 'test']);

Route::post('/register',RegisterController::class);
Route::post('/verify-email',VerifyEmailController::class);
Route::post('/login',LoginController::class);
Route::post('/forgot-password',[LoginNoPasswordController::class,'forgotPassword'],);
Route::post('/reset-password',[LoginNoPasswordController::class,'resetPassword'],);

Route::middleware(['auth:sanctum'])->group(function(){
    Route::get('/profile',[ProfileController::class,'index']);
    Route::post('/profile/update',[ProfileController::class,'update']);
    Route::put('/profile/update-password',[ProfileController::class,'updatePassword']);
    Route::post('/logout',[ProfileController::class,'logout']);

    Route::get('/categories',[CategoriesController::class, 'index']);
    Route::post('/categories/create',[CategoriesController::class, 'store']);
    Route::get('/category/show/{id}',[CategoriesController::class, 'show']);
    Route::post('/categories/update/{id}',[CategoriesController::class, 'update']);
    Route::delete('/categories/delete/{id}',[CategoriesController::class, 'delete']);

    Route::get('/shops',[ShopsController::class, 'index']);
    Route::get('/my-shops',[ShopsController::class, 'myShops']);
    Route::get('/banned-shops',[ShopsController::class, 'bannedShops']);
    Route::get('/shops/search',[ShopsController::class, 'search']);
    Route::get('/shop/show/{id}',[ShopsController::class, 'show']);
    Route::post('/shops/create',[ShopsController::class, 'store']);
    Route::put('/shops/update/{id}',[ShopsController::class, 'update']);
    Route::delete('/shops/ban/{id}',[ShopsController::class, 'ban']);
    Route::post('/shops/unban/{id}',[ShopsController::class, 'unban']);
    Route::delete('/shops/delete/{id}',[ShopsController::class, 'delete']);

    Route::get('/products/{id}',[ProductsController::class, 'index']);
    Route::get('/products/shop/{id}',[ProductsController::class, 'shopProducts']);
    Route::get('/products/banned',[ProductsController::class, 'bannedProducts']);
    Route::get('/products/search/{id}',[ProductsController::class, 'search']);
    Route::get('/product/show/{id}',[ProductsController::class, 'show']);
    Route::post('/products/create',[ProductsController::class, 'store']);
    Route::post('/products/update/{id}',[ProductsController::class, 'update']);
    Route::delete('/products/ban/{id}',[ProductsController::class, 'ban']);
    Route::post('/products/unban/{id}',[ProductsController::class, 'unban']);
    Route::delete('/products/delete/{id}',[ProductsController::class, 'delete']);

    Route::get('/Translations/category/{id}',[TranslationsController::class, 'categoryLangs']);
    Route::post('/Translations/category/add/{id}',[TranslationsController::class, 'addToCategory']);
    Route::get('/Translations/product/{id}',[TranslationsController::class, 'productLangs']);
    Route::post('/Translations/product/add/{id}',[TranslationsController::class, 'addToProduct']);

    Route::post('/comments/create',[CommentsController::class, 'store']);
    Route::put('/comments/update/{id}',[CommentsController::class, 'update']);
    Route::delete('/comments/delete/{id}',[CommentsController::class, 'delete']);

    Route::get('/carts',[CartsController::class, 'index']);
    Route::post('/carts/create',[CartsController::class, 'store']);
    Route::post('/cart/add/{id}',[CartsController::class, 'AddToCart']);
    Route::get('/cart/show/{id}',[CartsController::class, 'show']);
    Route::put('/cart/remove/{id}',[CartsController::class, 'RemoveFromCart']);
    Route::delete('/cart/delete/{id}',[CartsController::class, 'delete']);

    Route::get('/orders/user',[OrdersController::class, 'index']);
    Route::get('/orders/all',[OrdersController::class, 'allOrders']);
    Route::get('/order/show/{id}',[OrdersController::class, 'show']);
    Route::post('/orders/create',[OrdersController::class, 'store']);
    Route::put('/orders/update/{id}',[OrdersController::class, 'update']);
    Route::delete('/orders/delete/{id}',[OrdersController::class, 'delete']);
});
