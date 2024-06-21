<?php

namespace App\DataAccessLayer\Repository\Proxies;

use App\DataAccessLayer\DbModels\Album;
use App\DataAccessLayer\Repository\Implementations\AlbumRepository;
use App\DataAccessLayer\Repository\Interfaces\IAlbumRepository;
use App\Services\CacheServices\AlbumCacheService;


class AlbumRepositoryProxy extends AlbumRepository implements IAlbumRepository
{
    public function __construct(
        private readonly AlbumCacheService $albumCacheService,
        private readonly AlbumRepository $albumRepository
    ) {}

    public function getById(int $albumId): Album
    {
        $album = $this->albumCacheService->getAlbumFromCache($albumId);

        if (!$album) {
            $album = $this->albumRepository->getById($albumId);
            $this->albumCacheService->saveAlbumToCache($album);
        }

        return $album;
    }

    public function updateNameAndGenre(int $albumId, string $name, int $genreId): void {
        $this->albumRepository->updateNameAndGenre($albumId, $name, $genreId);
        $this->albumCacheService->deleteAlbumFromCache($albumId);
    }

    public function updatePublishTime(int $albumId, string $publishTime): void
    {
        $this->albumRepository->updatePublishTime($albumId, $publishTime);
        $this->albumCacheService->deleteAlbumFromCache($albumId);
    }

    public function makePublic(int $albumId): void
    {
        $this->albumRepository->makePublic($albumId);
        $this->albumCacheService->deleteAlbumFromCache($albumId);
    }

    public function updatePhoto(int $albumId, string $photoPath): void
    {
        $this->albumRepository->updatePhoto($albumId, $photoPath);
        $this->albumCacheService->deleteAlbumFromCache($albumId);
    }

    public function delete(int $albumId): void
    {
        $this->albumRepository->delete($albumId);
        $this->albumCacheService->deleteAlbumFromCache($albumId);
    }
}
