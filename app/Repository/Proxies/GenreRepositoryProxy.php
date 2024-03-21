<?php

namespace App\Repository\Proxies;

use App\Models\Genre;
use App\Repository\Implementations\GenreRepository;
use App\Repository\Interfaces\IGenreRepository;
use App\Services\CacheServices\GenreCacheService;

class GenreRepositoryProxy implements IGenreRepository
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

    public function getMultipleByIds(array $genresIds): array
    {
        return $this->genreRepository->getMultipleByIds($genresIds);
    }

    public function getAll(): array
    {
        return $this->genreRepository->getAll();
    }
}
