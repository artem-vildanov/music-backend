<?php

return [
    'algorithm' => 'HS256',
    'key' => env('JWT_KEY'),
    'ttl' => env('JWT_EXP_SEC'),
    'ttr' => env('JWT_REFRESH_SEC'),
    'allow_multiple_unlimited_tokens' => env('ALLOW_MULTIPLE_UNLIMITED_TOKENS'),
    'token_expiration_time' => env('TOKEN_EXPIRATION_TIME_SEC'),
    'expired_timeToLive_message' => 'token expired',
    'expired_timeToRefresh_message' => 'token expired, not refreshable'
];
