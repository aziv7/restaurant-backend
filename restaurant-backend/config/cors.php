<?php

$domain = parse_url($_SERVER['HTTP_REFERER']);
    $host = '*';
    if (isset($domain['host'])) {
        $host = $domain['host'];
    }

return [

    /*
    |--------------------------------------------------------------------------
    | Cross-Origin Resource Sharing (CORS) Configuration
    |--------------------------------------------------------------------------
    |
    | Here you may configure your settings for cross-origin resource sharing
    | or "CORS". This determines what cross-origin operations may execute
    | in web browsers. You are free to adjust these settings as needed.
    |
    | To learn more: https://developer.mozilla.org/en-US/docs/Web/HTTP/CORS
    |
    */

    'paths' => ['api/*', 'sanctum/csrf-cookie', '*'],

    'allowed_methods' => ['*'],

    'allowed_origins' => ['http://localhost:3000','http://localhost:4200','http://localhost:8100', '$host'],

    'allowed_origins_patterns' => [],

    'allowed_headers' => ['Origin', 'Content-Type', 'X-XSRF-TOKEN', 'Authorization', '*'],

    'exposed_headers' => [],

    'max_age' => 0,

    'supports_credentials' => true,

];