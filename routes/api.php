<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Reward\ShowCouponController;
use App\Http\Controllers\Reward\SpendPointsController;
use App\Http\Controllers\API\ProductController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Reward\UserCouponsController;
use App\Http\Controllers\Api\CartController;


Route::post('/login', LoginController::class);

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/register', RegisterController::class)->middleware('throttle:registration');

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/spend-points', SpendPointsController::class);
    
    Route::get('/my-coupons', UserCouponsController::class);
    
    Route::get('/coupons/{coupon}', ShowCouponController::class);

    Route::post('/orders', [OrderController::class, 'store'])->middleware('throttle:5,1');

    Route::post('/cart/calculate', [CartController::class, 'calculate'])
         ->middleware('throttle:30,1');

    Route::get('/products', [ProductController::class, 'index']);
    Route::post('/orders', [OrderController::class, 'store']);

});