<?php

namespace Mahedi250\Bkash\app\Service;

use Mahedi250\Bkash\app\Util\BkashCredential;
use Illuminate\Support\Facades\Redis;
use Mahedi250\Bkash\app\Service\BkashService;
use Exception;
use Illuminate\Support\Str;

class CheckoutUrlService extends BkashService
{
  private $credential;
  public function __construct()
  {
    parent::__construct('tokenized');
    $this->credential = new BkashCredential(config('bkash.tokenized.sandbox2'));
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
    Redis::command('SET', [$key, json_encode($log)]);
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
          'app_key' => $this->credential->appKey,
          'app_secret' => $this->credential->appSecret,
          'refresh_token' => $refreshToken,
        ],
        'headers' => $this->credential->getAuthHeaders(),
      ]);

      return json_decode($res->getBody()->getContents(), true);
    } catch (Exception $e) {
      throw $e;
    }
  }

  public function createPayment($amount,$invoiceNumber= nul)
  {
    try {
      $token= $this->grantToken($this->credential);
      $url = $this->credential->getURL('/checkout/create');
      $headers = $this->credential->getAccessHeaders($token['id_token']);

      $body = [
        "mode"=> "0011",
        'payerReference' => ' ',
        'currency' => 'BDT',
        'callbackURL' => $this->credential->getCallBackURL(),
        'amount' => strval($amount * 1.0),
        'intent' => 'sale',
        'merchantInvoiceNumber' => $invoiceNumber? $invoiceNumber : str::random(20),
      ];
      $res = $this->httpClient()->post($url, [
        'json' => $body,
        'headers' => $headers,
      ]);



      $response = json_decode($res->getBody()->getContents(), true);



      //$this->storeLog('create_payment', $url, $headers, $body, $response);

      return $response;
    } catch (Exception $e) {
      throw $e;
    }
  }

  public function executePayment($paymentID)
  {
    try {
        $token= $this->grantToken($this->credential);
      $url = $this->credential->getURL('/checkout/execute/');
      $headers = $this->credential->getAccessHeaders($token['id_token']);
      $body = [
        'paymentID'=> $paymentID
      ];
      $res = $this->httpClient()->post($url, [
        'json' => $body,
        'headers' => $headers,
      ]);

      $response = json_decode($res->getBody()->getContents(), true);
      //dd($response);



      return $response;
    } catch (Exception $e) {
      throw $e;
    }
  }



  public function queryPayment($paymentID)
  {
    try {
      $token= $this->grantToken($this->credential);
      $url = $this->credential->getURL('/checkout/payment/status');
      $headers = $this->credential->getAccessHeaders($token['id_token']);
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
        $token= $this->grantToken($this->credential);
      $url = $this->credential->getURL('/checkout/general/searchTransaction');
      $headers = $this->credential->getAccessHeaders($token['id_token']);
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
        $token= $this->grantToken($this->credential);
      $res = $this->httpClient()->post($this->credential->getURL('/checkout/payment/refund'), [
        'json' => [
          'paymentID' => $paymentID,
          'trxID' => $trxID,
          'amount' => strval($amount),
          'sku' => 'no SKU',
          'reason' => 'Product quality issue'
        ],
        'headers' => $this->credential->getAccessHeaders($token['id_token']),
      ]);

      return json_decode($res->getBody()->getContents(), true);
    } catch (Exception $e) {
      throw $e;
    }
  }
}
