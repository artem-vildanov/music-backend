<?php


namespace App\DataAccessLayer\Repository\Proxies;

use App\DataAccessLayer\DbModels\Artist;
use App\DataAccessLayer\Repository\Implementations\ArtistRepository;
use App\DataAccessLayer\Repository\Interfaces\IArtistRepository;
use App\Services\CacheServices\ArtistCacheService;

class ArtistRepositoryProxy extends ArtistRepository implements IArtistRepository
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


