<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'resend' => [
        'key' => env('RESEND_KEY'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    'paymob' => [
        'base_url' => env('PAYMOB_BASE_URL'),
        'hmac_key' => env('PAYMOB_HMAC_KEY'),
        'api_key' => env('PAYMOB_API_KEY'),
        'security_key' => env('PAYMOB_SECURITY_KEY'),
        'public_key' => env('PAYMOB_PUBLIC_KEY'),
        'integrations' =>  ['OnlineCard' => 5683778, 'MobileWallet' => 5683914, 'PayPal' => 5683860]
    ],

    'tap' => [
        'base_url' => env('TAP_BASE_URL'),
        'api_key' => env('TAP_API_KEY'),
        'public_key' => env('TAP_PUBLIC_KEY'),
    ],
];
