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

    /*
    |--------------------------------------------------------------------------
    | remove.bg (Background Removal for Player Photo Cutouts)
    |--------------------------------------------------------------------------
    | Used by PlayerObserver and the players:generate-cutouts command.
    | Stored in config (not env() directly) so it survives `artisan config:cache`.
    */
    'remove_bg' => [
        'key' => env('REMOVE_BG_API_KEY'),
    ],

    /*
    |--------------------------------------------------------------------------
    | rembg (local background-removal CLI fallback)
    |--------------------------------------------------------------------------
    | Absolute path to the `rembg` binary. Useful when the web/PHP-FPM process
    | has a minimal PATH and cannot locate rembg via `command -v`.
    */
    'rembg' => [
        'path' => env('REMBG_PATH'),
    ],

];
