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

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
        'scheme' => 'https',
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ticafrique' => [
        'api_key' => env('TICAFRIQUE_API_KEY', 'sk_a12069fbaf0a2fc394556ef9af030cbdde33c4bf4032a67c9ff2cf290e562cff'),
        'api_url' => env('TICAFRIQUE_API_URL', 'https://sms.ticafrique.ci/api/v1/sms/send'),
        'sender_id' => env('TICAFRIQUE_SENDER_ID', 'AKADI'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],


    // socialite ... ,
    'google' => [
        'client_id' => env('GOOGLE_CLIENT_ID'),
        'client_secret' => env('GOOGLE_CLIENT_SECRET'),
        'redirect' => env('GOOGLE_CLIENT_CALLBACK')
    ],

    'facebook' => [
        'client_id' => env('FACEBOOK_CLIENT_ID'),
        'client_secret' => env('FACEBOOK_CLIENT_SECRET'),
        'redirect' => env('FACEBOOK_CLIENT_CALLBACK')
    ],

    'github' => [
        'client_id' => env('GITHUB_CLIENT_ID'),
        'client_secret' => env('GITHUB_CLIENT_SECRET'),
        'redirect' => env('GITHUB_CLIENT_CALLBACK')
    ],

    'linkedin' => [
        'client_id' => env('LINKEDIN_CLIENT_ID'),
        'client_secret' => env('LINKEDIN_CLIENT_SECRET'),
        'redirect' => env('LINKEDIN_CLIENT_CALLBACK')
    ],



    // whatsapp send message
    'whatsapp' => [
        'token' => env('WHATSAPP_TOKEN'),
        'phone_number_id' => env('WHATSAPP_PHONE_NUMBER_ID'),
    ],

    //twilio
    'twilio' => [
        'sid' => env('TWILIO_ACCOUNT_SID'),
        'token' => env('TWILIO_AUTH_TOKEN'),
        'from' => env('TWILIO_WHATSAPP_FROM'),
    ],
    // sms service
    'sms' => [
        'username' => env('SMS_USERNAME'),
        'password' => env('SMS_PASSWORD'),
        'sender' => env('SMS_SENDER'),
        'url' => env('SMS_URL'),
    ],

    // Wave Payment Gateway
    'wave' => [
        'api_key' => env('WAVE_API_KEY'),
        'api_url' => env('WAVE_API_URL', 'https://api.wave.com/v1/checkout/sessions'),
        'success_url' => env('WAVE_SUCCESS_URL'),
        'error_url' => env('WAVE_ERROR_URL'),
    ],


];
