<?php

namespace Mahedi250\Bkash\Facade;

use Illuminate\Support\Facades\Facade;


/**
 * @method static Create($amount,['payerReference'=>'01877722345','merchantInvoiceNumber'=>'inv.123'])
 * @method static Execute($paymentID)
 */
class CheckoutUrl extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'CheckoutUrl';
    }
}
