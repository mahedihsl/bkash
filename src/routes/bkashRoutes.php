<?php

use Illuminate\Support\Facades\Route;
use Mahedi250\Bkash\App\Controllers\CheckoutUrlController;
use Mahedi250\Bkash\Facade\CheckoutUrl;

Route::group(['middleware' => ['web']], function () {


    Route::get("/bkash",function(){
        // session(['test_key' => 'test_value']);
        $response = CheckoutUrl::Create(100);

            return redirect($response['bkashURL']);

    });

    Route::get("bkash/callback",[CheckoutUrlController::class,'Callback']);
    Route::get("bkash/failed",[CheckoutUrlController::class,'Failed'])->name('bkash.payment.fail');
    Route::get("bkash/success",[CheckoutUrlController::class,'Success'])->name('bkash.payment.success');
    // Your routes here
});













