<?php

namespace App\Repository\Interfaces;

use App\Exceptions\DataAccessExceptions\DataAccessException;
use App\Models\Artist;

interface IArtistRepository
{
    /**
     * @param int $artistId
     * @throws DataAccessException
     * @return Artist
     */
    public function getById(int $artistId): Artist;

    /**
     * @param int[] $artistIds
     * @return Artist[]
     */
    public function getMultipleByIds(array $artistIds): array;

    /**
     * поиск артиста, который управляется пользователем
     * @param int $userId
     * @throws DataAccessException
     * @return Artist
     */
    public function getByUserId(int $userId): Artist;

    /**
     * @param string $name
     * @param string $photoPath
     * @param int $userId
     * @throws DataAccessException
     * @return int
     */
    public function create(
        string $name,
        string $photoPath,
        int $userId
    ): int;

    /**
     * @param int $artistId
     * @param string $name
     * @throws DataAccessException
     * @return void
     */
    public function update(
        int $artistId,
        string $name
    ): void;

    /**
     * @param int $artistId
     * @throws DataAccessException
     * @return void
     */
    public function delete(int $artistId): void;

}
