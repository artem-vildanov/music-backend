<?php

namespace App\DataAccessLayer\Repository\Proxies;

use App\DataAccessLayer\DbMappers\AlbumDbMapper;
use App\DataAccessLayer\DbModels\Album;
use App\DataAccessLayer\Repository\Implementations\AlbumRepository;
use App\DataAccessLayer\Repository\Interfaces\IAlbumRepository;
use App\Services\CacheServices\AlbumCacheService;


class AlbumRepositoryProxy extends AlbumRepository implements IAlbumRepository
{
    public function __construct(
        private readonly AlbumCacheService $albumCacheService,
        private readonly AlbumRepository $albumRepository,
        AlbumDbMapper $albumDbMapper
    ) {
        parent::__construct($albumDbMapper);
    }

    public function getById(string $albumId): Album
    {
        $album = $this->albumCacheService->getAlbumFromCache($albumId);

        if (!$album) {
            $album = $this->albumRepository->getById($albumId);
            $this->albumCacheService->saveAlbumToCache($album);
        }

        return $album;
    }

    public function updateName(string $albumId, string $name): void {
        $this->albumRepository->updateName($albumId, $name);
        $this->albumCacheService->deleteAlbumFromCache($albumId);
    }

    public function updatePublishTime(string $albumId, string $publishTime): void
    {
        $this->albumRepository->updatePublishTime($albumId, $publishTime);
        $this->albumCacheService->deleteAlbumFromCache($albumId);
    }

    public function makePublic(string $albumId): void
    {
        $this->albumRepository->makePublic($albumId);
        $this->albumCacheService->deleteAlbumFromCache($albumId);
    }

    public function updatePhoto(string $albumId, string $photoPath): void
    {
        $this->albumRepository->updatePhoto($albumId, $photoPath);
        $this->albumCacheService->deleteAlbumFromCache($albumId);
    }

    public function delete(string $albumId): void
    {
        $this->albumRepository->delete($albumId);
        $this->albumCacheService->deleteAlbumFromCache($albumId);
    }
}
