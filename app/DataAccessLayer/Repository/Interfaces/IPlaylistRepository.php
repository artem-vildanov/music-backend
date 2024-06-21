<?php

namespace App\DataAccessLayer\Repository\Interfaces;

use App\DataAccessLayer\DbModels\Playlist;
use App\Exceptions\DataAccessExceptions\DataAccessException;

interface IPlaylistRepository
{
    /**
     * @param int $playlistId
     * @throws DataAccessException
     * @return Playlist
     */
    public function getById(int $playlistId): Playlist;

    /**
     * @param int[] $playlistsIds
     * @return Playlist[]
     */
    public function getMultipleByIds(array $playlistsIds): array;

    /**
     * @param int $userId
     * @return Playlist[]
     */
    public function getPlaylistsModelsByUserId(int $userId): array;

    /**
     * @param int $userId
     * @return int[]
     */
    public function getPlaylistsIdsByUserId(int $userId): array;

    /**
     * @param string $name
     * @param int $userId
     * @throws DataAccessException
     * @return int playlistId
     */
    public function create(string $name, int $userId): int;

    /**
     * @param int $playlistId
     * @param string $name
     * @throws DataAccessException
     * @return void
     */
    public function updateName(int $playlistId, string $name): void;

    public function updatePhoto(int $playlistId, string $photoPath): void;

    /**
     * @param int $playlistId
     * @throws DataAccessException
     * @return void
     */
    public function delete(int $playlistId): void;
}
