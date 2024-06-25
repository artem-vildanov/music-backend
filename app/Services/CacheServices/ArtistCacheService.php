<?php

namespace App\Services\CacheServices;

use App\DataAccessLayer\DbModels\Artist;

class ArtistCacheService
{
    public function __construct(
        private readonly CacheStorageService $cacheStorageService
    ) {}

    public function saveArtistToCache(Artist $artist): void
    {
        $serializedArtist = serialize($artist);
        $idInRedis = "artist_{$artist->id}";

        $this->cacheStorageService->saveToCache($idInRedis, $serializedArtist);
    }

    public function getArtistFromCache(string $artistId): ?Artist
    {
        $idInRedis = "artist_{$artistId}";

        $serializedArtist = $this->cacheStorageService->getFromCache($idInRedis);
        if (!$serializedArtist) {
            return null;
        }

        return unserialize($serializedArtist);
    }

    public function deleteArtistFromCache(string $artistId): void
    {
        $idInRedis = "artist_{$artistId}";
        $this->cacheStorageService->deleteFromCache($idInRedis);
    }
}
