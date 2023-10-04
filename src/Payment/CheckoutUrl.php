<?php

namespace Mahedi250\Bkash\Payment;

use Mahedi250\Bkash\app\Service\CheckoutUrlService;



class CheckoutUrl
{
    private $checkoutUrl;

    public function __construct()
    {
        $this->checkoutUrl= new CheckoutUrlService();

    }


    public function Create($amount,$invoiceNumber=null)
    {

        return $this->checkoutUrl->createPayment($amount,$invoiceNumber);

    }
    public function Execute($paymentID)
    {

        return $this->checkoutUrl->executePayment($paymentID);

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


}
