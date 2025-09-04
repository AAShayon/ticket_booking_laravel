<?php

return [
    'api_domain' => env('SSLCOMMERZ_API_DOMAIN', 'https://sandbox.sslcommerz.com'),
    'store_id' => env('SSLCOMMERZ_STORE_ID'),
    'store_passwd' => env('SSLCOMMERZ_STORE_PASSWD'),
    'success_url' => env('SSLCOMMERZ_SUCCESS_URL', '/api/payment/success'),
    'fail_url' => env('SSLCOMMERZ_FAIL_URL', '/api/payment/fail'),
    'cancel_url' => env('SSLCOMMERZ_CANCEL_URL', '/api/payment/cancel'),
    'ipn_url' => env('SSLCOMMERZ_IPN_URL', '/api/payment/ipn'),
    'currency' => env('SSLCOMMERZ_CURRENCY', 'BDT'),
    'sandbox' => env('SSLCOMMERZ_SANDBOX', true),
];