<?php

namespace App\DataAccessLayer\Repository\Proxies;

use App\DataAccessLayer\DbMappers\SongDbMapper;
use App\DataAccessLayer\DbModels\Song;
use App\DataAccessLayer\Repository\Implementations\SongRepository;
use App\DataAccessLayer\Repository\Interfaces\ISongRepository;
use App\Exceptions\DataAccessExceptions\DataAccessException;
use App\Services\CacheServices\SongCacheService;

class SongRepositoryProxy extends SongRepository implements ISongRepository
{
    public function __construct(
        private readonly SongCacheService $songCacheService,
        private readonly SongRepository $songRepository,
        SongDbMapper $songDbMapper
    ) {
        parent::__construct($songDbMapper);
    }

    /**
     * @throws DataAccessException
     */
    public function getById(string $songId): Song
    {
        $song = $this->songCacheService->getSongFromCache($songId);
        if (!$song) {
            $song = $this->songRepository->getById($songId);
            $this->songCacheService->saveSongToCache($song);
        }

        return $song;
    }

    /**
     * @throws DataAccessException
     */
    public function delete(string $songId): void
    {
        $this->songRepository->delete($songId);
        $this->songCacheService->deleteSongFromCache($songId);
    }

    /**
     * @throws DataAccessException
     */
    public function updateName(string $songId, string $name): void
    {
        $this->songRepository->updateName($songId, $name);
        $this->songCacheService->deleteSongFromCache($songId);
    }

    public function updatePhoto(string $songId, string $photoPath): void
    {
        $this->songRepository->updatePhoto($songId, $photoPath);
        $this->songCacheService->deleteSongFromCache($songId);
    }

    public function updateAudio(string $songId, string $musicPath): void
    {
        $this->songRepository->updateAudio($songId, $musicPath);
        $this->songCacheService->deleteSongFromCache($songId);
    }
}
