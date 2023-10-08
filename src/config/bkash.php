<?php

return [
  "BKASH_SANDBOX"         => env("BKASH_SANDBOX",true),
  "BKASH_CHECKOUT_URL_USER_NAME"     => env("BKASH_CHECKOUT_URL_USER_NAME", "sandboxTokenizedUser02"),
  "BKASH_CHECKOUT_URL_PASSWORD" => env("BKASH_CHECKOUT_URL_PASSWORD", "sandboxTokenizedUser02@12345"),
  "BKASH_CHECKOUT_URL_APP_KEY"      => env("BKASH_CHECKOUT_URL_APP_KEY", "4f6o0cjiki2rfm34kfdadl1eqq"),
  "BKASH_CHECKOUT_URL_APP_SECRET"     => env("BKASH_CHECKOUT_URL_APP_SECRET", "2is7hdktrekvrbljjh44ll3d9l1dtjo4pasmjvs5vl5qr3fug4b"),

  "BKASH_CHECKOUT_URL_BASE_URL_SANDBOX" => "https://tokenized.sandbox.bka.sh/v1.2.0-beta/tokenized",
  "BKASH_CHECKOUT_URL_BASE_URL_PRODUCTION" => "https://tokenized.Pay.bka.sh/v1.2.0-beta/tokenized",
  "callback_url"    => env("BKASH_CALLBACK_URL", "http://127.0.0.1:8000/bkash/callback"),


];
 ?>
