<?php


namespace App\Repository\Proxies;

use App\Repository\Implementations\ArtistRepository;
use App\Models\Artist;
use App\Repository\Interfaces\IArtistRepository;
use App\Services\CacheServices\ArtistCacheService;

class ArtistRepositoryProxy implements IArtistRepository
{
    public function __construct(
        private readonly ArtistCacheService $artistCacheService,
        private readonly ArtistRepository $artistRepository
    ) {}

    public function getById(int $artistId): Artist
    {
        $artist = $this->artistCacheService->getArtistFromCache($artistId);
        if (!$artist) {
            $artist = $this->artistRepository->getById($artistId);
            $this->artistCacheService->saveArtistToCache($artist);
        }

        return $artist;
    }

    public function getMultipleByIds(array $artistIds): array
    {
        return $this->artistRepository->getMultipleByIds($artistIds);
    }

    public function getByUserId(int $userId): Artist
    {
        return $this->artistRepository->getByUserId($userId);
    }

    public function create(string $name, string $photoPath, int $userId): int
    {
        return $this->artistRepository->create(
            $name,
            $photoPath, 
            $userId
        );
    }

    public function updateName(int $artistId, string $name): void
    {
        $this->artistRepository->updateName($artistId, $name);
        $this->artistCacheService->deleteArtistFromCache($artistId);
    }

    public function updatePhoto(int $artistId, string $photoPath): void
    {
        $this->artistRepository->updatePhoto($artistId, $photoPath);
        $this->artistCacheService->deleteArtistFromCache($artistId);
    }

    public function delete(int $artistId): void
    {
        $this->artistRepository->delete($artistId);
        $this->artistCacheService->deleteArtistFromCache($artistId);
    }
}


