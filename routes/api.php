<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LoginNoPasswordController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\VerifyEmailController;
use App\Http\Controllers\CategoriesController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TestController;
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
    Route::get('/categories/show/{id}',[CategoriesController::class, 'show']);
    Route::post('/categories/update/{id}',[CategoriesController::class, 'update']);
    Route::delete('/categories/delete/{id}',[CategoriesController::class, 'delete']);
});