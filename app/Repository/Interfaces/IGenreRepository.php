<?php

namespace App\Repository\Interfaces;

use App\Exceptions\DataAccessExceptions\DataAccessException;
use App\Models\Genre;

interface IGenreRepository {

    /**
     * @param int $genreId
     * @throws DataAccessException
     * @return Genre
     */
    public function getById(int $genreId): Genre;

    /**
     * @param int[] $genresIds
     * @return Genre[]
     */
    public function getMultipleByIds(array $genresIds): array;

    public function getAll(): array;
}
