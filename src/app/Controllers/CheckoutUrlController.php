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

        if ($status === 'success')
        {
            $response = CheckoutUrl::MakePayment($paymentId);

            if ($response['statusCode'] !== '0000')
            {
            return CheckoutUrl::Failed($response['statusMessage']);
            }

            if (array_key_exists('transactionStatus',$response)&&($response['transactionStatus']=='Completed'||$response['transactionStatus']=='Authorized'))
            {
                 //Database Insert Operation
                return CheckoutUrl::Success($response['trxID']."({$response['transactionStatus']})");
            }
            else if($response['transactionStatus']=='Initiated')
            {

                return CheckoutUrl::Failed("Try Again");
            }
        }

        else
        {
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
