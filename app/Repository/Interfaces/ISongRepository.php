<?php

namespace App\Repository\Interfaces;

use App\Exceptions\DataAccessExceptions\DataAccessException;
use App\Models\Song;

interface ISongRepository {
    /**
     * @param int $songId
     * @throws DataAccessException
     * @return Song
     */
    public function getById(int $songId): Song;

    /**
     * @param int[] $songsIds
     * @return Song[]
     */
    public function getMultipleByIds(array $songsIds): array;

    /**
     * @param int $albumId
     * @return array<Song>
     */
    public function getAllByAlbum(int $albumId): array;

    /**
     * @param string $name
     * @param string $photoPath
     * @param string $musicPath
     * @param int $albumId
     * @throws DataAccessException
     * @return int
     */
    public function create(string $name, string $photoPath, string $musicPath, int $albumId): int;

    /**
     * @param int $songId
     * @throws DataAccessException
     * @return void
     */
    public function delete(int $songId): void;

    /**
     * @param int $songId
     * @param string $name
     * @throws DataAccessException
     * @return void
     */
    public function update(int $songId, string $name): void;
}
