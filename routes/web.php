<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\PaymentCallbackController;

Route::get('/payment/callback/{order}', [PaymentCallbackController::class, 'handleCallback'])->name('payment.callback');
Route::get('/', function () {
    return view('welcome');
});
