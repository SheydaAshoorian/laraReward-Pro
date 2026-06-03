<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Reward\ShowCouponController;
use App\Http\Controllers\Reward\SpendPointsController;

use App\Http\Controllers\Auth\LoginController;

Route::post('/login', LoginController::class);

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/register', RegisterController::class)->middleware('throttle:registration');



use App\Http\Controllers\Reward\UserCouponsController;

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/spend-points', SpendPointsController::class);
    
    Route::get('/my-coupons', UserCouponsController::class);
    
    Route::get('/coupons/{coupon}', ShowCouponController::class);

});