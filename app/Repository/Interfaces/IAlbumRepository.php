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
     * @return array<Album>
     */
    public function getAllReadyToPublish(): array;

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
        int $genreId,
        ?string $publishTime,
        string $status
    ): int;

    /**
     * @param int $albumId
     * @param string $name
     * @param string $status
     * @param int $genreId
     * @throws DataAccessException
     * @return void
     */
    public function updateNameAndGenre(
        int $albumId,
        string $name,
        int $genreId
    ): void;

    public function makePublic(int $albumId): void;

    public function updatePublishTime(int $albumId, string $publishTime): void;

    public function updatePhoto(int $albumId, string $photoPath): void;

    /**
     * @param int $albumId
     * @throws DataAccessException
     * @return void
     */
    public function delete(int $albumId): void;
}
