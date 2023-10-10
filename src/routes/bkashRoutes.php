<?php

use Illuminate\Support\Facades\Route;
use Mahedi250\Bkash\app\Http\Controllers\CheckoutUrlController;





Route::get("bkash/callback",[CheckoutUrlController::class,'Callback']);
Route::get("bkash/failed",[CheckoutUrlController::class,'Failed']);
Route::get("bkash/success",[CheckoutUrlController::class,'Success']);











