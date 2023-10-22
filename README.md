# bKash Laravel Package

Welcome to the bKash Laravel Package! This package allows for seamless integration with the bKash payment gateway, making transactions a breeze.

## Installation

```bash
composer require mahedi250/bkash
```

### vendor publish (config)

```bash
php artisan vendor:publish --provider="Mahedi250\Bkash\bkashServiceProvider"
```

### Set .env configuration

```bash
 BKASH_SANDBOX=true
 BKASH_CHECKOUT_URL_USER_NAME = ''
 BKASH_CHECKOUT_URL_PASSWORD = ''
 BKASH_CHECKOUT_URL_APP_KEY = ''
 BKASH_CHECKOUT_URL_APP_SECRET = ''
 BKASH_CALLBACK_URL='Your defined Callback URl //defualt Callback url => http://127.0.0.1:8000/bkash/callback'

```

### Generate the Controller

```bash
php artisan make:controller Payment/BkashPaymentController

```

# [CHECKOUT (URL BASED)](https://developer.bka.sh/docs/checkout-url-process-overview)

### 1. Create Payment

```
<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Mahedi250\Bkash\Facade\CheckoutUrl;

class BkashPaymentController extends Controller
{
    public function pay(Request $request)
    {
        $amount = 100;
        $response = CheckoutUrl::Create($amount);
        return redirect($response['bkashURL']);
    }
}

```

#### To Pass Additional Body Parameter

```
$response = CheckoutUrl::Create(1000,['payerReference'=>"01877722345",'merchantInvoiceNumber'=>"Inv-12"]);

return redirect($response['bkashURL']);

```

### 2. ADD callback function

```
 public function callback(Request $request)
    {
        $status = $request->input('status');
        $paymentId = $request->input('paymentID');

        if ($status === 'success') {
            $response = CheckoutUrl::MakePayment($paymentId);

            if ($response['statusCode'] !== '0000') {
                return CheckoutUrl::Failed($response['statusMessage']);
                }
                if (array_key_exists('transactionStatus',$response)&&$response['transactionStatus']=='Completed'){

                return CheckoutUrl::Success($response['trxID']);

                }

        } else {
            return CheckoutUrl::Failed($status);
        }
    }

```

### 3. ADD routes in Web.php

```
Route::group(['middleware' => ['web']], function () {
    Route::post("bkash/pay",[BkashPaymentController::class,'pay'])->name('bkash.pay');
    Route::get("bkash/callback",[BkashPaymentController::class,'Callback']);
});

```

### 4. Use route('bkash.pay') in blade

```
<form action="{{ route('bkash.pay') }}" method="POST">
        @csrf
        <button type="submit">Pay with bkash</button>
    </form>

```
