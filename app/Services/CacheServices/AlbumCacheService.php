<?php

namespace App\Services\CacheServices;

use App\DataAccessLayer\DbModels\Album;
use App\Utils\Enums\ModelNames;
use App\Utils\Enums\UserRoles;

class AlbumCacheService
{
    private string $redisIdPrefix;
    public function __construct(
        private readonly CacheStorageService $cacheStorageService
    ) {
        $this->redisIdPrefix = ModelNames::Artist->value . '_';
    }

    public function saveAlbumToCache(Album $album): void
    {
        $serializedAlbum = serialize($album);
        $idInRedis = $this->redisIdPrefix . $album->_id;

        $this->cacheStorageService->saveToCache($idInRedis, $serializedAlbum);
    }

    public function getAlbumFromCache(string $albumId): ?Album
    {
        $idInRedis = $this->redisIdPrefix . $albumId;

        $serializedAlbum = $this->cacheStorageService->getFromCache($idInRedis);
        if (!$serializedAlbum) {
            return null;
        }

        return unserialize($serializedAlbum);
    }

    public function deleteAlbumFromCache(string $albumId): void
    {
        $idInRedis = $this->redisIdPrefix . $albumId;
        $this->cacheStorageService->deleteFromCache($idInRedis);
    }
}
