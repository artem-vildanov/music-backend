<?php

namespace App\DataAccessLayer\Repository\Interfaces;

use App\DataAccessLayer\DbModels\Album;
use App\Exceptions\DataAccessExceptions\DataAccessException;

interface IAlbumRepository {
    /**
     * @param string $albumId
     * @throws DataAccessException
     * @return Album
     */
    public function getById(string $albumId): Album;

    /**
     * @param string[] $albumsIds
     * @return Album[]
     */
//    public function getMultipleByIds(array $albumsIds): array;

    /**
     * @param string $artistId
     * @return array<Album>
     */
    public function getAllByArtist(string $artistId): array;

    public function getAllByGenre(string $genre);


    /**
     * @return array<Album>
     */
    public function getAllReadyToPublish(): array;

    public function create(
        string $name,
        string $photoPath,
        string $artistId,
        string $genre,
        ?string $publishTime,
    ): string;

    /**
     * @param string $albumId
     * @param string $name
     * @throws DataAccessException
     * @return void
     */
    public function updateName(string $albumId, string $name): void;
    public function makePublic(string $albumId): void;
    public function updatePublishTime(string $albumId, string $publishTime): void;
    public function updatePhoto(string $albumId, string $photoPath): void;
    public function updateGenre(string $albumId, string $genre): void;

    /**
     * @param string $id album id
     */
    public function incrementLikes(string $id): void;
    /**
     * @param string $id album id
     */
    public function decrementLikes(string $id): void;

    /**
     * @param string $albumId
     * @throws DataAccessException
     * @return void
     */
    public function delete(string $albumId): void;
}
