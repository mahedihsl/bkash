<?php

use Illuminate\Support\Facades\Route;
use Mahedi250\Bkash\Facade\bkashPayment;
use Illuminate\Http\Request;


Route::get("/hello",function(){

   return redirect(bkashPayment::Create(100)['bkashURL']);

});

Route::get("bkash/callback",function(Request $request){
    if($request->all()['status']=='success'){
        return  bkashPayment::Execute($request['paymentID']);

    }
    else {
        return $request->all()['status'];
    }


});






