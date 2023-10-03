<?php

namespace Mahedi250\Bkash\Service;

use Mahedi250\Bkash\Util\BkashCredential;
use Illuminate\Support\Facades\Redis;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TokenizedService extends BkashService
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


      //$this->storeLog('grant_token', $url, $headers, $body, $response);

      return $response;
    } catch (Exception $e) {
      throw $e;
    }
  }

  public function refreshToken($refreshToken, BkashCredential $credential)
  {
    //dd($credential->getURL('tokenized/checkout/token/refresh'));
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

 // "mode": "0000",
// "callbackURL": "yourdomain.com",
// "payerReference": "0173499999",

public function tokenizedCallback(Request $request){
    try {

        return $request;

      } catch (Exception $e) {
        throw $e;
      }



  }


  public function createAgreement($payerReference, BkashCredential $credential){
    try {
        $url = $credential->getURL('/checkout/create');
        $headers = $credential->getAccessHeaders($this->getAccessToken());
        $body = [
          'mode' => "0000",
          'callbackURL' => 'http://localhost:8000/tokenizedCallback',
          'payerReference' => "01877722345",
        ];
       //dd($headers);
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


  public function executeAgreement($paymentID, BkashCredential $credential){
    try {
        $url = $credential->getURL('/checkout/execute');
        $headers = $credential->getAccessHeaders($this->getAccessToken());
        $body = [
          'paymentID' => $paymentID,

        ];
       //dd($headers);
        $res = $this->httpClient()->post($url, [
          'json' => $body,
          'headers' => $headers,
        ]);
       // dd( $res);

        $response = json_decode($res->getBody()->getContents(), true);



        //$this->storeLog('create_payment', $url, $headers, $body, $response);

        return $response;
      } catch (Exception $e) {
        throw $e;
      }



  }


  /*{
   'agreementID':'TokenizedMerchant01L3IKB6H1565072174986'
   "mode": "0001",
   "payerReference": "01723888888",
   "callbackURL": "yourDomain.com",
   "merchantAssociationInfo": "MI05MID54RF09123456One"
   "amount": "12",
   "currency": "BDT",
   "intent": "sale",
   "merchantInvoiceNumber": "Inv0124"
} */


  public function createPayment($amount,$agreementID,BkashCredential $credential)
  {
    try {
      $url = $credential->getURL('/checkout/create');
      $headers = $credential->getAccessHeaders($this->getAccessToken());
      //dd(  $headers );
      $body = [
        "mode"=> "0011",
        'payerReference' => '',
        'currency' => 'BDT',
        'callbackURL' => 'http://localhost:8000/tokenizedCreateCallback',
        'amount' => strval($amount * 1.0),
        'intent' => 'sale',
        'merchantInvoiceNumber' => str::random(20),
      ];
     // dd($headers,$body);
      $res = $this->httpClient()->post($url, [
        'json' => $body,
        'headers' => $headers,
      ]);

      $response = json_decode($res->getBody()->getContents(), true);

      $this->storeLog('create_payment', $url, $headers, $body, $response);

      return $response;
    } catch (Exception $e) {
      throw $e;
    }
  }

  public function executePayment($paymentID, BkashCredential $credential)
  {


    try {
      $url = $credential->getURL('/checkout/execute');
      $headers = $credential->getAccessHeaders($this->getAccessToken());
     // dd($headers);
      $body = ["paymentID"=>$paymentID];
      $res = $this->httpClient()->post($url, [
        'json' => $body,
        'headers' => $headers,
      ]);

      $response = json_decode($res->getBody()->getContents(), true);
      //dd($response);

      $this->storeLog('execute_payment', $url, $headers, $body, $response);

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
      $url = $credential->getURL('/checkout/payment/status');
      $headers = $credential->getAccessHeaders($this->getAccessToken());
      $body = ["paymentID"=>$paymentID];

      $res = $this->httpClient()->Get($url, [
        'headers' => $headers,
        'json'=> $body ,
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
      $res = $this->httpClient()->post($credential->getURL('/checkout/payment/refund'), [
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
