<?php

use Illuminate\Support\Facades\Route;
use Mahedi250\Bkash\App\Controllers\CheckoutUrlController;
use Mahedi250\Bkash\Facade\CheckoutUrl;





Route::group(['middleware' => ['web']], function () {

    //Route::get("/bkash",function(){ return redirect(CheckoutUrl::Create(102,['payerReference'=>"01877722345",'intent'=>'authorization'])['bkashURL']);});
    Route::get("bkash/callback",[CheckoutUrlController::class,'Callback']);
    Route::get("bkash/failed",[CheckoutUrlController::class,'Failed'])->name('bkash.payment.fail');
    Route::get("bkash/success",[CheckoutUrlController::class,'Success'])->name('bkash.payment.success');

});













