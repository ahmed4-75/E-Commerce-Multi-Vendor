<?php

use App\Http\Controllers\TestPaymobController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/payment/test', [TestPaymobController::class, 'callBack']);

Route::get('/test-hmac', function() {

});
