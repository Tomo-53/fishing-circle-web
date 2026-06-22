<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Cross-Origin Resource Sharing (CORS) Configuration
    |--------------------------------------------------------------------------
    |
    | SPA（Next.js）との通信を許可するための設定。
    | supports_credentials=true は Sanctum SPA 認証（Cookie セッション）に必須。
    | allowed_origins に Next.js の開発・本番 URL を追加すること。
    |
    */

    'paths' => ['api/*', 'sanctum/csrf-cookie'],

    'allowed_methods' => ['*'],

    'allowed_origins' => array_filter([
        'http://localhost:3000',
        env('FRONTEND_URL'),
    ]),

    'allowed_origins_patterns' => [],

    'allowed_headers' => ['*'],

    'exposed_headers' => [],

    'max_age' => 0,

    // Cookie セッション認証に必須（true にすると allowed_origins に '*' は使えない）
    'supports_credentials' => true,

];
