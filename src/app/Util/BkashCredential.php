<?php

namespace Mahedi250\Bkash\App\Util;

class BkashCredential
{
  public $callbackurl;
  public $baseUrl;
  public $sandbox;
  public $appKey;
  public $appSecret;
  public $username;
  public $password;

  public function __construct($arr)
  {


    $baseKey = $arr['BKASH_SANDBOX'] ? 'BKASH_CHECKOUT_URL_BASE_URL_SANDBOX':'BKASH_CHECKOUT_URL_BASE_URL_PRODUCTION';
    $this->baseUrl = $arr[$baseKey];
    $this->appKey = $arr['BKASH_CHECKOUT_URL_APP_KEY'];
    $this->appSecret = $arr['BKASH_CHECKOUT_URL_APP_SECRET'];
    $this->username = $arr['BKASH_CHECKOUT_URL_USER_NAME'];
    $this->password = $arr['BKASH_CHECKOUT_URL_PASSWORD'];
    $this->callbackurl=$arr['callback_url'];
    $this->sandbox= $arr['BKASH_SANDBOX'];

  }

  public function getURL($path)
  {
    return $this->baseUrl.$path;
  }

  public function getCallBackURL()
  {
    return $this->callbackurl;
  }


  public function getAuthHeaders()
  {
    return [
      'Content-Type' => 'application/json',
      'Accept' => 'application/json',
      'username' => $this->username,
      'password' => $this->password,
    ];
  }

  public function getAccessHeaders($accessToken)
  {
    return [
      'Content-Type' => 'application/json',
      'Accept' => 'application/json',
      'Authorization' => $accessToken,
      'X-App-Key' => $this->appKey,
    ];
  }
}
