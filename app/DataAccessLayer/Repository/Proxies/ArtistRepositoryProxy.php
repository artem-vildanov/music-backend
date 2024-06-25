<?php

namespace App\DataAccessLayer\Repository\Proxies;

use App\DataAccessLayer\DbMappers\ArtistDbMapper;
use App\Services\CacheServices\ArtistCacheService;
use App\DataAccessLayer\Repository\Interfaces\IArtistRepository;
use App\DataAccessLayer\Repository\Implementations\ArtistRepository;
use App\DataAccessLayer\DbModels\Artist;

class ArtistRepositoryProxy extends ArtistRepository implements IArtistRepository
{
    public function __construct(
        private readonly ArtistCacheService $artistCacheService,
        private readonly ArtistRepository $artistRepository,
        ArtistDbMapper $artistDbMapper
    ) {
        parent::__construct($artistDbMapper);
    }

    public function getById(string $artistId): Artist
    {
        $artist = $this->artistCacheService->getArtistFromCache($artistId);
        if (!$artist) {
            $artist = $this->artistRepository->getById($artistId);
            $this->artistCacheService->saveArtistToCache($artist);
        }

        return $artist;
    }

    public function updateName(string $artistId, string $name): void
    {
        $this->artistRepository->updateName($artistId, $name);
        $this->artistCacheService->deleteArtistFromCache($artistId);
    }

    public function updatePhoto(string $artistId, string $photoPath): void
    {
        $this->artistRepository->updatePhoto($artistId, $photoPath);
        $this->artistCacheService->deleteArtistFromCache($artistId);
    }

    public function delete(string $artistId): void
    {
        $this->artistRepository->delete($artistId);
        $this->artistCacheService->deleteArtistFromCache($artistId);
    }
}


