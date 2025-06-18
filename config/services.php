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

    'forum_api' => [
        'login_url' => env('FORUM_API_LOGIN_URL', 'http://localhost:3000/api/login'),
        'weekly_recap_url' => env('FORUM_API_WEEKLY_RECAP_URL', 'http://localhost:3000/api/forum/weeklyrecap'),
        'monthly_recap_url' => env('FORUM_API_MONTHLY_RECAP_URL', 'http://localhost:3000/api/forum/monthlyrecap'),
        'username' => env('FORUM_API_USERNAME', 'adminforumaccess'), // Or testuser for monthly
        'password' => env('FORUM_API_PASSWORD', 'pass1234'), // Or testpassword for monthly
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

];
