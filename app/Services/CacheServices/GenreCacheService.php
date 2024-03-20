<?php

namespace App\Services\CacheServices;

use App\Models\Genre;

class GenreCacheService
{
    public function __construct(
        private readonly CacheStorageService $cacheStorageService
    ) {}

    public function saveGenreToCache(Genre $genre): void
    {
        $serializedGenre = serialize($genre);
        $idInRedis = "genre_{$genre->id}";

        $this->cacheStorageService->saveToCache($idInRedis, $serializedGenre);
    }

    public function getGenreFromCache(int $genreId): ?Genre
    {
        $idInRedis = "genre_{$genreId}";

        $serializedGenre = $this->cacheStorageService->getFromCache($idInRedis);
        if (!$serializedGenre) {
            return null;
        }

        return unserialize($serializedGenre);
    }

    public function deleteGenreFromCache(int $genreId): void
    {
        $idInRedis = "genre_{$genreId}";
        $this->cacheStorageService->deleteFromCache($idInRedis);
    }
}
