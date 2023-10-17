<?php

use Illuminate\Support\Facades\Route;
use Mahedi250\Bkash\App\Controllers\CheckoutUrlController;






Route::group(['middleware' => ['web']], function () {

    Route::get("bkash/callback",[CheckoutUrlController::class,'Callback']);
    Route::get("bkash/failed",[CheckoutUrlController::class,'Failed'])->name('bkash.payment.fail');
    Route::get("bkash/success",[CheckoutUrlController::class,'Success'])->name('bkash.payment.success');

});













