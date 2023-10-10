<?php
namespace Mahedi250\Bkash\app\Http\Controllers;

use Illuminate\Http\Request;
use Mahedi250\Bkash\Facade\CheckoutUrl;
use Illuminate\Routing\Controller ;

class CheckoutUrlController extends Controller
 {



    public function Callback(Request $request)
    {
        if($request->all()['status']=='success'){
            $response=CheckoutUrl::Execute($request['paymentID']);
            if($response['statusCode']!='0000'){

                return redirect('bkash/failed?status='.$response['statusMessage']);
            }

            return redirect('bkash/success?txrID='.$response['trxID']);

        }
        else {


            return redirect('bkash/failed?status='.$request->all()['status']);

        }

    }

   public function Failed(Request $request){

          $status= $request->query('status');
          return view('bkash::fail',compact('status'));

   }
   public function Success(Request $request){

          $txrID= $request->query('txrID');
          return view('bkash::success',compact('txrID'));

   }


}
