<?php

namespace App\Services\JwtServices;

use App\Exceptions\RedisException;
use App\Services\RedisServices\RedisStorageService;

class TokenStorageService
{
    public function __construct(
        private readonly RedisStorageService $redisStorageService,
    ) {}

    public function saveToken(string $token, int $userId): void
    {
        $timeToRefresh = (int)config('jwt.ttr');
        $this->redisStorageService->save($token, (string)$userId, $timeToRefresh);
    }

    /**
     * @throws RedisException
     */
    public function deleteToken(string $token): void
    {
        if (!$this->redisStorageService->delete($token)) {
            throw RedisException::failedToDeleteToken();
        }
    }

    /**
     * @throws RedisException
     */
    public function findToken(string $token): string
    {
        $userId = $this->redisStorageService->find($token);

        if (!$token) {
            throw RedisException::failedToFindToken();
        }

        return (string)$userId;
    }
}
