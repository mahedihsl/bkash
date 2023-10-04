<?php

use Illuminate\Support\Facades\Route;
use Mahedi250\Bkash\Facade\bkashPayment;
use Mahedi250\Bkash\Facade\CheckoutUrl;
use Illuminate\Http\Request;


Route::get("/bkash",function(){

    //return bkashPayment::Create(100);

   return redirect(CheckoutUrl::Create(100)['bkashURL']);

});

Route::get("bkash/callback",function(Request $request){
    if($request->all()['status']=='success'){
        return  CheckoutUrl::Execute($request['paymentID']);

    }
    else {
        return $request->all()['status'];
    }


});

Route::get("bkash/quary/{paymentID}",function($paymentID){

    return  CheckoutUrl::Query($paymentID);
});
Route::get("bkash/serach/{trxID}",function($trxID){

    return  CheckoutUrl::Search($trxID);
});
Route::get("bkash/refund",function(){

    return  CheckoutUrl::refund('TR0011ztwBNPQ1696419411358','AJ460FKP56','100');
});






