<?php

namespace App\Services\CacheServices;

use App\Models\Playlist;

class PlaylistCacheService
{
    public function __construct(
        private readonly CacheStorageService $cacheStorageService
    ) {}

    public function savePlaylistToCache(Playlist $playlist): void
    {
        $serializedPlaylist = serialize($playlist);
        $idInRedis = "playlist_{$playlist->id}";

        $this->cacheStorageService->saveToCache($idInRedis, $serializedPlaylist);
    }

    public function getPlaylistFromCache(int $playlistId): ?Playlist
    {
        $idInRedis = "playlist_{$playlistId}";

        $serializedPlaylist = $this->cacheStorageService->getFromCache($idInRedis);
        if (!$serializedPlaylist) {
            return null;
        }

        return unserialize($serializedPlaylist);
    }

    public function deletePlaylistFromCache(int $playlistId): void
    {
        $idInRedis = "playlist_{$playlistId}";
        $this->cacheStorageService->deleteFromCache($idInRedis);
    }
}
