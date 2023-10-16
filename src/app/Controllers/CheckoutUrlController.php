<?php
namespace Mahedi250\Bkash\App\Controllers;

use Illuminate\Http\Request;
use Mahedi250\Bkash\Facade\CheckoutUrl;
use Illuminate\Routing\Controller ;

class CheckoutUrlController extends Controller
 {



    public function callback(Request $request)
    {
        $status = $request->input('status');
        $paymentId = $request->input('paymentID');

        if ($status === 'success') {
            $response = CheckoutUrl::execute($paymentId);

            if ($response['statusCode'] !== '0000') {
                return CheckoutUrl::failed($response['statusMessage']);
            }

            return CheckoutUrl::success($response['trxID']);
        } else {
            return CheckoutUrl::failed($status);
        }
    }

   public function Failed(){

          return view('bkash::fail');

   }
   public function Success(){

          return view('bkash::success');

   }


}
