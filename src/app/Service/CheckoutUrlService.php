<?php

namespace Mahedi250\Bkash\app\Service;

use Mahedi250\Bkash\app\Util\BkashCredential;
use Illuminate\Support\Facades\Redis;
use Mahedi250\Bkash\app\Service\BkashService;
use Exception;
use Illuminate\Support\Str;

class CheckoutUrlService extends BkashService
{
  public function __construct()
  {
    parent::__construct('tokenized');
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

  public function grantToken(BkashCredential $credential)
  {
    try {
      $url = $credential->getURL('/checkout/token/grant');
      $headers = $credential->getAuthHeaders();


      $body = [
        'app_key' => $credential->appKey,
        'app_secret' => $credential->appSecret,
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

  public function refreshToken($refreshToken, BkashCredential $credential)
  {
    try {
      $res = $this->httpClient()->post($credential->getURL('/checkout/token/refresh'), [
        'json' => [
          'app_key' => $credential->appKey,
          'app_secret' => $credential->appSecret,
          'refresh_token' => $refreshToken,
        ],
        'headers' => $credential->getAuthHeaders(),
      ]);

      return json_decode($res->getBody()->getContents(), true);
    } catch (Exception $e) {
      throw $e;
    }
  }

  public function createPayment($amount,$invoiceNumber= null,BkashCredential $credential)
  {
    try {
      $token= $this->grantToken($credential);
      $url = $credential->getURL('/checkout/create');
      $headers = $credential->getAccessHeaders($token['id_token']);

      $body = [
        "mode"=> "0011",
        'payerReference' => ' ',
        'currency' => 'BDT',
        'callbackURL' => $credential->getCallBackURL(),
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

  public function executePayment($paymentID, BkashCredential $credential)
  {
    try {
        $token= $this->grantToken($credential);
      $url = $credential->getURL('/checkout/execute/');
      $headers = $credential->getAccessHeaders($token['id_token']);
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

  /**
   * trxID: 9G5507A7EH
   * paymentID: 8YQNJVY1657017309523
   * refundTrxID: 9G5307A7KT
   */

  public function queryPayment($paymentID, BkashCredential $credential)
  {
    try {
      $url = $credential->getURL('/checkout/payment/query/') . $paymentID;
      $headers = $credential->getAccessHeaders($this->getAccessToken());
      $body = [];
      $res = $this->httpClient()->get($url, [
        'headers' => $headers,
      ]);

      $response = json_decode($res->getBody()->getContents(), true);

      $this->storeLog('query_payment', $url, $headers, $body, $response);

      return $response;
    } catch (Exception $e) {
      throw $e;
    }
  }

  public function searchTransaction($trxID, BkashCredential $credential)
  {
    try {
      $url = $credential->getURL('/checkout/payment/search/') . $trxID;
      $headers = $credential->getAccessHeaders($this->getAccessToken());
      $body = [];
      $res = $this->httpClient()->get($url, [
        'headers' => $headers,
      ]);

      $response = json_decode($res->getBody()->getContents(), true);
      $this->storeLog('search_transaction', $url, $headers, $body, $response);

      return $response;
    } catch (Exception $e) {
      throw $e;
    }
  }

  public function refundTransaction($paymentID, $trxID, $amount, BkashCredential $credential)
  {
    try {
      $res = $this->httpClient()->post($credential->getURL('/checkout/payment/refund/'), [
        'json' => [
          'paymentID' => $paymentID,
          'trxID' => $trxID,
          'amount' => strval($amount),
          'sku' => 'no SKU',
          'reason' => 'Product quality issue'
        ],
        'headers' => $credential->getAccessHeaders($this->getAccessToken()),
      ]);

      return json_decode($res->getBody()->getContents(), true);
    } catch (Exception $e) {
      throw $e;
    }
  }
}
