<?php

namespace App\Repository;

use App\Exceptions\DataAccessExceptions\DataAccessException;
use App\Exceptions\DataAccessExceptions\GenreException;
use App\Models\Genre;
use App\Repository\Interfaces\IGenreRepository;
use App\Services\CacheServices\GenreCacheService;

class GenreRepository implements IGenreRepository
{
    public function __construct(
        private readonly GenreCacheService $genreCacheService
    ) {}

    /**
     * @inheritDoc
     * @throws \App\Exceptions\DataAccessExceptions\DataAccessException
     */
    public function getById(int $genreId): Genre
    {
        $genre = $this->genreCacheService->getGenreFromCache($genreId);
        if ($genre) {
            return $genre;
        }

        $genre = Genre::query()->find($genreId);

        if (!$genre) {
            throw GenreException::notFound($genreId);
        }

        $this->genreCacheService->saveGenreToCache($genre);

        return $genre;
    }

    public function getMultipleByIds(array $genresIds): array
    {
        return Genre::query()->whereIn('id', $genresIds)->get()->all();
    }

    public function getAll(): array
    {
        return Genre::query()->get()->all();
    }
}
