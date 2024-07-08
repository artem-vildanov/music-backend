<?php

namespace App\Services\JwtServices;

use App\Exceptions\RedisException;
use App\Services\RedisServices\RedisStorageService;

class TokenStorageService
{
    public function __construct(
        private readonly RedisStorageService $redisStorageService,
    ) {}

    public function saveToken(string $token, string $userId): void
    {
        $timeToRefresh = (int)config('jwt.ttr');
        $this->redisStorageService->save($token, $userId, $timeToRefresh);
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

        if (!$userId) {
            throw RedisException::failedToFindToken();
        }

        return $userId;
    }
}
