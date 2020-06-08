<?php

return [
    'store_id' => env('SSLCOMMERZ_STORE_ID', 'store_id'),
    'store_password' => env('SSLCOMMERZ_STORE_PASSWORD', 'password'),
    'currency' =>  env('SSLCOMMERZ_CURRENCY', 'BDT'),
    'success_url' => env('SSLCOMMERZ_SUCCESS_URL', 'http://example.com/success.php'),
    'fail_url' => env('SSLCOMMERZ_FAIL_URL', 'http://example.com/fail.php'),
    'cancel_url' => env('SSLCOMMERZ_CANCEL_URL', 'http://example.com/cancel.php'),
    'ipn_url' => env('SSLCOMMERZ_IPN_URL', 'http://example.com/ipn.php'),
    'sandbox_mode' => env('SSLCOMMERZ_MODE', 'sandbox')
];
