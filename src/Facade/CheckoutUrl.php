<?php

namespace Mahedi250\Bkash\Facade;

use Illuminate\Support\Facades\Facade;


/**
 * @method static create($amount, $invoice)
 * @method static verify($paymentRefId)
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
