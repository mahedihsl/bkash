<?php

namespace Mahedi250\Bkash\App\Service;

use Mahedi250\Bkash\App\Util\BkashCredential;
use Mahedi250\Bkash\App\Exceptions\BkashException;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use DateTime;


class BkashAuthService extends BkashService
{


    private $credential;
    private static $instance;
    public function __construct()
    {

        parent::__construct('tokenized');
        $this->credential = new BkashCredential(config('bkash'));


    }



    public function getToken()
    {
    $token = Session::get('bkash_token');


    Log::info("bkashAuthService---first"."=>".json_encode($token));

    if (!$token) {
        $tokenArray = [
            'id_token' => $this->grantToken()['id_token'],
            'sandbox' => $this->credential->sandbox,
            'store_time' => now(),
        ];

        Session::put('bkash_token', $tokenArray);

        Log::info("bkashAuthService---> previously no token found");

        return $tokenArray['id_token'];
    } else {
        if ($token['sandbox'] != $this->credential->sandbox) {
            $tokenArray = [
                'id_token' => $this->grantToken()['id_token'],
                'sandbox' => $this->credential->sandbox,
                'store_time' => now(),
            ];
            Session::put('bkash_token', $tokenArray);
            Log::info("bkashAuthService---> env cahnged new token generated");
            return $tokenArray['id_token'];
        }
        else if ($this->hasExceededTimeLimit($token['store_time'])) {
            $tokenArray = [
                'id_token' => $this->grantToken()['id_token'],
                'sandbox' => $this->credential->sandbox,
                'store_time' => now(),
            ];
            Session::put('bkash_token', $tokenArray);
            Log::info("bkashAuthService---> Token time excced new token generated");
            return $tokenArray['id_token'];
        }


        else {
            Log::info("bkashAuthService---Token from existing session");
            return $token['id_token'];
        }
    }
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

     // $this->storeLog('grant_token', $url, $headers, $body, $response);

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

   private function hasExceededTimeLimit($storeTime, $minutesLimit = 50) {
        // Parse the stored time as a DateTime object
        $storedTime = new DateTime($storeTime);

        // Get the current time as a DateTime object
        $currentTime = new DateTime();

        // Calculate the difference in minutes
        $interval = $storedTime->diff($currentTime);
        $minutesDifference = $interval->days * 24 * 60 + $interval->h * 60 + $interval->i;

        // Check if the difference exceeds the specified minutes limit
        return $minutesDifference > $minutesLimit;
    }




}
