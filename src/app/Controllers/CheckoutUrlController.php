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
            $response = CheckoutUrl::Execute($paymentId);

            if ($response['statusCode'] !== '0000') {
                return CheckoutUrl::Failed($response['statusMessage']);
            }

            return CheckoutUrl::Success($response['trxID']);
        } else {
            return CheckoutUrl::Failed($status);
        }
    }

   public function Failed(){

          return view('bkash::fail');

   }
   public function Success(){

          return view('bkash::success');

   }


}
