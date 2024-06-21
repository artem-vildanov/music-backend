<?php

namespace App\DataAccessLayer\Repository\Interfaces;

use App\DataAccessLayer\DbModels\Genre;
use App\Exceptions\DataAccessExceptions\DataAccessException;

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
