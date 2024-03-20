<?php

namespace App\Services\CacheServices;

use App\Exceptions\DataAccessExceptions\DataAccessException;
use App\Models\Album;
use App\Models\Artist;
use App\Repository\Interfaces\IArtistRepository;

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

    public function getArtistFromCache(int $artistId): ?Artist
    {
        $idInRedis = "artist_{$artistId}";

        $serializedArtist = $this->cacheStorageService->getFromCache($idInRedis);
        if (!$serializedArtist) {
            return null;
        }

        return unserialize($serializedArtist);
    }

    public function deleteArtistFromCache(int $artistId): void
    {
        $idInRedis = "album_{$artistId}";
        $this->cacheStorageService->deleteFromCache($idInRedis);
    }
}
