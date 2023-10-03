<?php

namespace Mahedi250\Bkash\Payment;

use Mahedi250\Bkash\Service\CheckoutService;
use Mahedi250\Bkash\Service\TokenizedService;
use Mahedi250\Bkash\app\Util\BkashCredential;
use Mahedi250\Bkash\app\Service\CheckoutUrlService;



class Payment
{
    private $credential;

    public function __construct()
    {

        $this->credential = new BkashCredential(config('bkash.tokenized.sandbox2'));
    }


    public function Create($amount,$invoiceNumber=null)
    {

        $checkoutUrl= new CheckoutUrlService();

        return $checkoutUrl->createPayment($amount,$invoiceNumber,$this->credential);

    }
    public function Execute($paymentID)
    {

        $checkoutUrl= new CheckoutUrlService();
        return $checkoutUrl->executePayment($paymentID,$this->credential);

    }


}
