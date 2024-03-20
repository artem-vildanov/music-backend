<?php

namespace App\Services\CacheServices;

use App\Services\RedisServices\RedisStorageService;

class CacheStorageService
{
    public function __construct(
        private readonly RedisStorageService $redisStorageService
    ) {}

    public function saveToCache(string $objectId, string $serializedObject): void
    {
        $timeToLive = 60;
        $this->redisStorageService->save($objectId, $serializedObject, $timeToLive);
    }

    public function getFromCache(string $objectId): ?string
    {
        return $this->redisStorageService->find($objectId);
    }

    public function deleteFromCache(string $objectId): void
    {
        $this->redisStorageService->delete($objectId);
    }
}
