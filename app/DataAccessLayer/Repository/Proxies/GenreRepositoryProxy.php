<?php

namespace App\DataAccessLayer\Repository\Proxies;

use App\DataAccessLayer\DbModels\Genre;
use App\DataAccessLayer\Repository\Implementations\GenreRepository;
use App\DataAccessLayer\Repository\Interfaces\IGenreRepository;
use App\Services\CacheServices\GenreCacheService;

class GenreRepositoryProxy extends GenreRepository implements IGenreRepository
{
    public function __construct(
        private readonly GenreCacheService $genreCacheService,
        private readonly GenreRepository $genreRepository
    ) {}

    /**
     * @inheritDoc
     * @throws \App\Exceptions\DataAccessExceptions\DataAccessException
     */
    public function getById(int $genreId): Genre
    {
        $genre = $this->genreCacheService->getGenreFromCache($genreId);
        if (!$genre) {
            $genre = $this->genreRepository->getById($genreId);
            $this->genreCacheService->saveGenreToCache($genre);
        }

        return $genre;
    }
}
