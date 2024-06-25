<?php

namespace App\DataAccessLayer\Repository\Interfaces;

use App\DataAccessLayer\DbModels\Artist;
use App\Exceptions\DataAccessExceptions\DataAccessException;

interface IArtistRepository
{
    public function getAll(): array;

    /**
     * @param string $artistId
     * @throws DataAccessException
     * @return Artist
     */
    public function getById(string $artistId): Artist;

    /**
     * поиск артиста, который управляется пользователем
     * @param string $userId
     * @throws DataAccessException
     * @return Artist
     */
    public function getByUserId(string $userId): Artist;

    /**
     * @param string $name
     * @param string $photoPath
     * @param string $userId
     * @throws DataAccessException
     * @return string
     */
    public function create(
        string $name,
        string $photoPath,
        string $userId
    ): string;

    /**
     * @param string $artistId
     * @param string $name
     * @throws DataAccessException
     * @return void
     */
    public function updateName(
        string $artistId,
        string $name
    ): void;

    public function updatePhoto(
        string $artistId,
        string $photoPath
    ): void;

    /**
     * @param string $id artist id
     */
    public function incrementLikes(string $id): void;
    /**
     * @param string $id artist id
     */
    public function decrementLikes(string $id): void;

    /**
     * @param string $artistId
     * @throws DataAccessException
     * @return void
     */
    public function delete(string $artistId): void;

}
