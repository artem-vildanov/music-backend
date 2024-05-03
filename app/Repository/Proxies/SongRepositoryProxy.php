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

    public function create(string $name, string $photoPath, string $musicPath, int $albumId, int $artistId): int
    {
        return $this->songRepository->create(
            $name,
            $photoPath,
            $musicPath,
            $albumId,
            $artistId
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
    public function updateName(int $songId, string $name): void
    {
        $this->songRepository->updateName($songId, $name);
        $this->songCacheService->deleteSongFromCache($songId);
    }

    public function updatePhoto(int $songId, string $photoPath): void
    {
        $this->songRepository->updatePhoto($songId, $photoPath);
        $this->songCacheService->deleteSongFromCache($songId);
    }

    public function updateAudio(int $songId, string $musicPath): void
    {
        $this->songRepository->updateAudio($songId, $musicPath);
        $this->songCacheService->deleteSongFromCache($songId);
    }
}
