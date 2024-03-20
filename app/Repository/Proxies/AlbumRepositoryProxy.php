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

    public function create(
        string $name,
        string $photoPath,
        int $artistId,
        int $genreId
    ): int {
        return $this->albumRepository->create(
            $name,
            $photoPath,
            $artistId,
            $genreId
        );
    }

    public function update(
        int $albumId,
        string $name,
        string $status,
        int $genreId
    ): void {
        $this->albumRepository->update(
            $albumId,
            $name,
            $status,
            $genreId
        );

        $this->albumCacheService->deleteAlbumFromCache($albumId);
    }

    public function delete(int $albumId): void
    {
        $this->albumRepository->delete($albumId);
        $this->albumCacheService->deleteAlbumFromCache($albumId);
    }
}
