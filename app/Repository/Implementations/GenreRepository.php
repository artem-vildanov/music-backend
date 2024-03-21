<?php

namespace App\Repository\Implementations;

use App\Exceptions\DataAccessExceptions\GenreException;
use App\Models\Genre;
use App\Repository\Interfaces\IGenreRepository;

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
