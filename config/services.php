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
        'key' => env('POSTMARK_API_KEY'),
    ],

    'resend' => [
        'key' => env('RESEND_API_KEY'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    'gemini' => [
        'api_key' => env('GEMINI_API_KEY'),
        'model' => env('GEMINI_MODEL', 'gemini-1.5-flash'),
    ],

    'groq' => [
        'api_key' => env('GROQ_API_KEY'),
    ],

    'qwen' => [
        'api_key' => env('QWEN_API_KEY'),
        'model' => env('QWEN_MODEL', 'Qwen/Qwen3-4B-Instruct-2507'),
        'base_url' => env('QWEN_BASE_URL', 'https://api.bytez.com/models/v2'),
    ],

    'mayar' => [
        'api_key' => env('MAYAR_API_KEY'),
        'base_url' => env('MAYAR_BASE_URL', 'https://api.mayar.id/hl/v1'),
        'webhook_secret' => env('MAYAR_WEBHOOK_SECRET'),
    ],

    'ai_provider' => env('AI_PROVIDER', 'gemini'),

    'openrouter' => [
        'api_key' => env('OPENROUTER_API_KEY'),
        'model' => env('OPENROUTER_MODEL', 'google/gemini-2.0-flash-001'),
    ],
];
