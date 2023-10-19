<?php

namespace Mahedi250\Bkash\Products;

use Mahedi250\Bkash\app\Service\CheckoutUrlService;




class CheckoutUrl
{
    private $checkoutUrl;

    public function __construct()
    {
        $this->checkoutUrl= new CheckoutUrlService();

    }


    public function Create($amount,$options=[])
    {

        return $this->checkoutUrl->createPayment($amount,$options);

    }
    public function Execute($paymentID)
    {

        return $this->checkoutUrl->executePayment($paymentID);

    }
    public function MakePayment($paymentID)
    {

        return $this->checkoutUrl->makePayment($paymentID);

    }
    public function Query($paymentID)
    {

        return $this->checkoutUrl->queryPayment($paymentID);

    }
    public function Search($trxID)
    {

        return $this->checkoutUrl->searchTransaction($trxID);

    }
    public function Refund($paymentID, $trxID, $amount)
    {

        return $this->checkoutUrl->refundTransaction($paymentID, $trxID, $amount);

    }
    public function Capture($paymentID)
    {

        return $this->checkoutUrl->capturePayment($paymentID);

    }
    public function Void($paymentID)
    {

        return $this->checkoutUrl->voidPayment($paymentID);

    }
    public function Failed($message)
    {

        return $this->checkoutUrl->Failed($message);

    }
    public function Success($txrID)
    {

        return $this->checkoutUrl->Success($txrID);

    }


}
