<?php

namespace App\Services\CacheServices;

use App\Exceptions\DataAccessExceptions\DataAccessException;
use App\Models\Album;
use App\Repository\Interfaces\IAlbumRepository;

class AlbumCacheService
{
    public function __construct(
        private readonly CacheStorageService $cacheStorageService
    ) {}

    public function saveAlbumToCache(Album $album): void
    {
        $serializedAlbum = serialize($album);
        $idInRedis = "album_{$album->id}";

        $this->cacheStorageService->saveToCache($idInRedis, $serializedAlbum);
    }

    public function getAlbumFromCache(int $albumId): ?Album
    {
        $idInRedis = "album_{$albumId}";

        $serializedAlbum = $this->cacheStorageService->getFromCache($idInRedis);
        if (!$serializedAlbum) {
            return null;
        }

        return unserialize($serializedAlbum);
    }

    public function deleteAlbumFromCache(int $albumId): void
    {
        $idInRedis = "album_{$albumId}";
        $this->cacheStorageService->deleteFromCache($idInRedis);
    }
}
