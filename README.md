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
 BKASH_CALLBACK_URL='Your defined Callback URl'

```

### Generate the controller

```bash
php artisan make:controller Payment/BkashPaymentController

```

## CHECKOUT (URL BASED)

### 1. Create Payment

```
<?php

namespace App\Payment;

use App\Http\Controllers\Controller;
use Mahedi250\Bkash\Facade\CheckoutUrl;

class BkashPaymentController extends Controller
{
    public function pay(Request $request)
    {
        $amount = $request->input('amount');
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
            $response = CheckoutUrl::Execute($paymentId);

            if ($response['statusCode'] !== '0000') {
                return CheckoutUrl::Failed($response['statusMessage']);
            }

            return CheckoutUrl::Success($response['trxID']);
        } else {
            return CheckoutUrl::Failed($status);
        }
    }

```

### 3. ADD routes in Web.php

```
Route::group(['middleware' => ['web']], function () {
    Route::post("bkash/pay",[BkashPaymentController::class,'pay']);
    Route::get("bkash/callback",[BkashPaymentController::class,'Callback']);
});

```
