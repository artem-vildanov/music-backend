<?php

namespace App\Repository\Proxies;

use App\Exceptions\DataAccessExceptions\DataAccessException;
use App\Models\Song;
use App\Repository\Implementations\SongRepository;
use App\Repository\Interfaces\ISongRepository;
use App\Services\CacheServices\SongCacheService;

class SongRepositoryProxy implements ISongRepository
{
    public function __construct(
        private readonly SongCacheService $songCacheService,
        private readonly SongRepository $songRepository
    ) {}

    /**
     * @throws DataAccessException
     */
    public function getById(int $songId): Song
    {
        $song = $this->songCacheService->getSongFromCache($songId);
        if (!$song) {
            $song = $this->songRepository->getById($songId);
            $this->songCacheService->saveSongToCache($song);
        }

        return $song;
    }

    public function getMultipleByIds(array $songsIds): array
    {
        return $this->songRepository->getMultipleByIds($songsIds);
    }

    public function getAllByAlbum(int $albumId): array
    {
        return $this->songRepository->getAllByAlbum($albumId);
    }

    public function create(string $name, string $photoPath, string $musicPath, int $albumId): int
    {
        return $this->songRepository->create(
            $name,
            $photoPath,
            $musicPath,
            $albumId
        );
    }

    /**
     * @throws DataAccessException
     */
    public function delete(int $songId): void
    {
        $this->songRepository->delete($songId);
        $this->songCacheService->deleteSongFromCache($songId);
    }

    /**
     * @throws DataAccessException
     */
    public function update(int $songId, string $name): void
    {
        $this->songRepository->update($songId, $name);
        $this->songCacheService->deleteSongFromCache($songId);
    }
}
