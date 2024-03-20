<?php

namespace App\Repository\Interfaces;

use App\Exceptions\DataAccessExceptions\DataAccessException;
use App\Models\Album;

interface IAlbumRepository {
    /**
     * @param int $albumId
     * @throws DataAccessException
     * @return Album
     */
    public function getById(int $albumId): Album;

    /**
     * @param int[] $albumsIds
     * @return Album[]
     */
    public function getMultipleByIds(array $albumsIds): array;

    /**
     * @param int $artistId
     * @return array<Album>
     */
    public function getAllByArtist(int $artistId): array;

    public function getAllByGenre(int $genreId);

    /**
     * @param string $name
     * @param string $photoPath
     * @param int $artistId
     * @param int $genreId
     * @throws DataAccessException
     * @return int
     */
    public function create(
        string $name,
        string $photoPath,
        int $artistId,
        int $genreId
    ): int;

    /**
     * @param int $albumId
     * @param string $name
     * @param string $status
     * @param int $genreId
     * @throws DataAccessException
     * @return void
     */
    public function update(
        int $albumId,
        string $name,
        string $status,
        int $genreId
    ): void;

    /**
     * @param int $albumId
     * @throws DataAccessException
     * @return void
     */
    public function delete(int $albumId): void;
}
