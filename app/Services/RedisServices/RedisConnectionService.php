<?php

namespace App\Services\RedisServices;

use Predis\Client;

class RedisConnectionService
{
    private static ?Client $redisClient = null;

    public static function makeConnection(): Client
    {
        if (self::$redisClient === null) {
            self::$redisClient = new Client([
                'scheme' => env('REDIS_SCHEME'),
                'host'   => env('REDIS_HOST'),
                'port'   => env('REDIS_PORT')
            ]);
        }
        return self::$redisClient;
    }
}
