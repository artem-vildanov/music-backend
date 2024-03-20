<?php

namespace App\Services\CacheServices;

use App\Models\Song;

class SongCacheService
{
    public function __construct(
        private readonly CacheStorageService $cacheStorageService
    ) {}

    public function saveSongToCache(Song $song): void
    {
        $serializedSong = serialize($song);
        $idInRedis = "song_{$song->id}";

        $this->cacheStorageService->saveToCache($idInRedis, $serializedSong);
    }

    public function getSongFromCache(int $songId): ?Song
    {
        $idInRedis = "song_{$songId}";

        $serializedSong = $this->cacheStorageService->getFromCache($idInRedis);
        if (!$serializedSong) {
            return null;
        }

        return unserialize($serializedSong);
    }

    public function deleteSongFromCache(int $songId): void
    {
        $idInRedis = "song_{$songId}";
        $this->cacheStorageService->deleteFromCache($idInRedis);
    }
}
