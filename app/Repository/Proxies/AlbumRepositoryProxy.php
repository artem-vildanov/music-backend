<?php

namespace App\Repository\Proxies;

use App\Models\Album;
use App\Repository\Interfaces\IAlbumRepository;
use App\Services\CacheServices\AlbumCacheService;
use App\Repository\Implementations\AlbumRepository;


class AlbumRepositoryProxy implements IAlbumRepository
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

    public function getMultipleByIds(array $albumsIds): array
    {
        return $this->albumRepository->getMultipleByIds($albumsIds);
    }

    public function getAllByArtist(int $artistId): array
    {
        return $this->albumRepository->getAllByArtist($artistId);
    }

    public function getAllByGenre(int $genreId)
    {
        // TODO: Implement getAllByGenre() method.
    }

    public function getAllReadyToPublish(): array
    {
        return $this->albumRepository->getAllReadyToPublish();
    }

    public function create(
        string $name,
        string $photoPath,
        int $artistId,
        int $genreId,
        string $publishTime
    ): int {
        return $this->albumRepository->create(
            $name,
            $photoPath,
            $artistId,
            $genreId,
            $publishTime
        );
    }

    public function updateNameAndGenre(
        int $albumId,
        string $name,
        int $genreId,
    ): void {
        $this->albumRepository->updateNameAndGenre(
            $albumId,
            $name,
            $genreId
        );

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
