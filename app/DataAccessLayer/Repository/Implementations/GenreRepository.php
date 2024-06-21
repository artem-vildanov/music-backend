<?php

namespace App\DataAccessLayer\Repository\Implementations;

use App\DataAccessLayer\DbModels\Genre;
use App\DataAccessLayer\Repository\Interfaces\IGenreRepository;
use App\Exceptions\DataAccessExceptions\GenreException;

class GenreRepository implements IGenreRepository
{
    /**
     * @inheritDoc
     * @throws \App\Exceptions\DataAccessExceptions\DataAccessException
     */
    public function getById(int $genreId): Genre
    {
        $genre = Genre::query()->find($genreId);

        if (!$genre) {
            throw GenreException::notFound($genreId);
        }

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
