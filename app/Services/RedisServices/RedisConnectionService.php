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
                'scheme' => 'tcp',
                'host'   => 'redis', 
                'port'   => '6379'
            ]);
        }
        return self::$redisClient;
    }
}
