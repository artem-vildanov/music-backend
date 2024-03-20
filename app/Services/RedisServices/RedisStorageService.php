<?php

namespace App\Services\RedisServices;

use Predis\Client;

class RedisStorageService
{
    private Client $redis;
    public function __construct()
    {
        $this->redis = RedisConnectionService::makeConnection();
    }

    public function save(string $key, string $value, int $timeToLive): void
    {
        $this->redis->set($key, $value, 'EX', $timeToLive);
    }

    public function delete(string $key): bool
    {
        return (bool)$this->redis->del($key);
    }

    public function find(string $key): ?string
    {
        return $this->redis->get($key);
    }
}
