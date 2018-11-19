<?php

return [
    'client_id' => env('OPENID_CLIENT_ID'),
    'client_secret' => env('OPENID_CLIENT_SECRET'),
    'redirect' => env('OPENID_URL_REDIRECT'),
    'auth' => env('OPENID_URL_AUTH'),
    'token' => env('OPENID_URL_TOKEN'),
    'keys' => env('OPENID_URL_KEYS'),
    'guzzle' => [],
];
