<?php

namespace Mahedi250\Bkash\App\Service;

use Mahedi250\Bkash\App\Util\BkashCredential;
use Mahedi250\Bkash\App\Service\BkashService;
use Mahedi250\Bkash\App\Service\BkashAuthService;
use Illuminate\Support\Str;
use Mahedi250\Bkash\App\Exceptions\BkashException;
use Illuminate\Support\Facades\Log;
use Exception;

class CheckoutUrlService extends BkashService
{
  private $credential;
  private $token;
  private $bkashAuthService ;
  public function __construct()
  {

    parent::__construct('tokenized');
    $this->credential = new BkashCredential(config('bkash'));
    $this->bkashAuthService = new BkashAuthService();

  }

  private function storeLog($apiName, $url, $headers, $body, $response)
  {
    $log = [
      'url' => $url,
      'headers' => $headers,
      'body' => $body,
      'response' => $response,
    ];
    $key = 'bkash:log:' . $apiName;

    Log::info($key."=>".json_encode($log));

  }

  public function grantToken()
  {
    try {
      $url = $this->credential->getURL('/checkout/token/grant');
      $headers = $this->credential->getAuthHeaders();

      $body = [
        'app_key' => $this->credential->appKey,
        'app_secret' => $this->credential->appSecret,
      ];

      $res = $this->httpClient()->post($url, [
        'json' => $body,
        'headers' => $headers,
      ]);

      $response = json_decode($res->getBody()->getContents(), true);


      if($response['statusCode']!='0000')
      { throw new BkashException(json_encode($response));

    }

    $this->storeLog('grant_token', $url, $headers, $body, $response);

      return $response;
    } catch (Exception $e) {
      throw $e;
    }
  }

  public function refreshToken($refreshToken)
  {
    try {
      $res = $this->httpClient()->post($this->credential->getURL('/checkout/token/refresh'), [
        'json' => [
          'App_key' => $this->credential->appKey,
          'App_secret' => $this->credential->appSecret,
          'refresh_token' => $refreshToken,
        ],
        'headers' => $this->credential->getAuthHeaders(),
      ]);

      return json_decode($res->getBody()->getContents(), true);
    } catch (Exception $e) {
      throw $e;
    }
  }

  public function createPayment($amount,$options)
  {
    $defaults = [
        'payerReference' => '11',
        'intent' => 'sale',
        'merchantInvoiceNumber' => str::random(20)
    ];

    $options = array_merge($defaults, $options);

    if ($options['intent'] !== 'sale' && $options['intent'] !== 'authorization') {
        throw new BkashException('Invalid value for "intent". Allowed values are "sale" or "authorization".');
    }

    try {

      $url = $this->credential->getURL('/checkout/create');
      $headers = $this->credential->getAccessHeaders($this->bkashAuthService->getToken());

      $body = [
        "mode"=> "0011",
        'payerReference' =>$options['payerReference'],
        'currency' => 'BDT',
        'callbackURL' => $this->credential->getCallBackURL(),
        'amount' => strval($amount * 1.0),
        'intent' => $options['intent'],
        'merchantInvoiceNumber' => $options['merchantInvoiceNumber']
      ];


      $res = $this->httpClient()->post($url, [
        'json' => $body,
        'headers' => $headers,
      ]);



      $response = json_decode($res->getBody()->getContents(), true);

      if($response['statusCode']!='0000')
      {
        throw new BkashException(json_encode($response));
      }


      $this->storeLog('create_payment', $url, $headers, $body, $response);

      return $response;
    } catch (Exception $e) {
      throw $e;
    }
  }

  public function executePayment($paymentID)
  {
    try {

      $url = $this->credential->getURL('/checkout/execute/');
      $headers = $this->credential->getAccessHeaders($this->bkashAuthService->getToken());
      $body = [
        'paymentID'=> $paymentID
      ];

      $res = $this->httpClient()->post($url, [
        'json' => $body,
        'headers' => $headers,
      ]);

      $response = json_decode($res->getBody()->getContents(), true);



      $this->storeLog('Execute Payment', $url, $headers, $body, $response);


      return $response;
    } catch (Exception $e) {
      throw $e;
    }
  }



  public function queryPayment($paymentID)
  {
    try {

      $url = $this->credential->getURL('/checkout/payment/status');
      $headers = $this->credential->getAccessHeaders($this->bkashAuthService->getToken());
      $body = ['paymentID'=>$paymentID];
      $res = $this->httpClient()->post($url, [
        'json'=> $body,
        'headers' => $headers,
      ]);

      $response = json_decode($res->getBody()->getContents(), true);

      $this->storeLog('query_payment', $url, $headers, $body, $response);

      return $response;
    } catch (Exception $e) {
      throw $e;
    }
  }

  public function searchTransaction($trxID)
  {
    try {

      $url = $this->credential->getURL('/checkout/general/searchTransaction');
      $headers = $this->credential->getAccessHeaders($this->bkashAuthService->getToken());
      $body = ['trxID'=>$trxID];
      $res = $this->httpClient()->post($url, [
        'json'=> $body,
        'headers' => $headers,
      ]);

      $response = json_decode($res->getBody()->getContents(), true);
      $this->storeLog('search_transaction', $url, $headers, $body, $response);

      return $response;
    } catch (Exception $e) {
      throw $e;
    }
  }

  public function refundTransaction($paymentID, $trxID, $amount)
  {
    try {

      $res = $this->httpClient()->post($this->credential->getURL('/checkout/payment/refund'), [
        'json' => [
          'paymentID' => $paymentID,
          'trxID' => $trxID,
          'amount' => strval($amount),
          'sku' => 'no SKU',
          'reason' => 'Product quality issue'
        ],
        'headers' => $this->credential->getAccessHeaders($this->bkashAuthService->getToken()),
      ]);

      return json_decode($res->getBody()->getContents(), true);
    } catch (Exception $e) {
      throw $e;
    }
  }


  public function capturePayment($paymentID)
  {
    try {

      $url = $this->credential->getURL('/checkout/payment/confirm/capture');
      $headers = $this->credential->getAccessHeaders($this->bkashAuthService->getToken());
      $body = [
        'paymentID'=> $paymentID
      ];
      $res = $this->httpClient()->post($url, [
        'json' => $body,
        'headers' => $headers,
      ]);

      $response = json_decode($res->getBody()->getContents(), true);

      return $response;
    } catch (Exception $e) {
      throw $e;
    }
  }
  public function voidPayment($paymentID)
  {
    try {

      $url = $this->credential->getURL('/checkout/payment/confirm/void');
      $headers = $this->credential->getAccessHeaders($this->bkashAuthService->getToken());
      $body = [
        'paymentID'=> $paymentID
      ];
      $res = $this->httpClient()->post($url, [
        'json' => $body,
        'headers' => $headers,
      ]);

      $response = json_decode($res->getBody()->getContents(), true);

      return $response;
    } catch (Exception $e) {
      throw $e;
    }
  }

  public function makePayment($paymentID)
  {
        $startTimestamp = time();
        $response = $this->executePayment($paymentID);
        $endTimestamp = time();

        if ( isset($response) && array_key_exists('statusCode',$response))
        {

            return $response;
        }

        if ($endTimestamp - $startTimestamp > 30 && array_key_exists("message",$response))
        {
            // If the executePayment took too long, call the query API
            $queryResponse = $this->queryPayment($paymentID);

            return $queryResponse;
        }

  }

  public function Failed($message){

    session()->put('payment_Fail_message', $message);
    return redirect()->route('bkash.payment.fail');
}
  public function Success($txrID){

    session()->put('payment_confirm_message',$txrID);

    return redirect()->route('bkash.payment.success');
}
}
