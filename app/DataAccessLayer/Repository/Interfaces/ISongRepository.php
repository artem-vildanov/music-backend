<?php

namespace App\DataAccessLayer\Repository\Interfaces;

use App\DataAccessLayer\DbModels\Song;
use App\Exceptions\DataAccessExceptions\DataAccessException;

interface ISongRepository {
    /**
     * @param string $songId
     * @throws DataAccessException
     * @return Song
     */
    public function getById(string $songId): Song;

    /**
     * @param string[] $songsIds
     * @return Song[]
     */
    public function getMultipleByIds(array $songsIds): array;

    /**
     * @param string $albumId
     * @return array<Song>
     */
    public function getAllByAlbum(string $albumId): array;

    /**
     * @param string $name
     * @param string $photoPath
     * @param string $musicPath
     * @param string $albumId
     * @throws DataAccessException
     * @return string
     */
    public function create(
        string $name,
        string $photoPath,
        string $musicPath,
        string $albumId,
        string $artistId
    ): string;

    /**
     * @param string $songId
     * @throws DataAccessException
     * @return void
     */
    public function delete(string $songId): void;

    /**
     * @param string $songId
     * @param string $name
     * @throws DataAccessException
     * @return void
     */
    public function updateName(string $songId, string $name): void;

    public function updatePhoto(string $songId, string $photoPath): void;

    public function updateAudio(string $songId, string $musicPath): void;

    /**
     * @param string $id song id
     */
    public function incrementLikes(string $id): void;
    /**
     * @param string $id song id
     */
    public function decrementLikes(string $id): void;
}
