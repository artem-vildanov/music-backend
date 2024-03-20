<?php

namespace App\Repository\Interfaces;

use App\Exceptions\DataAccessExceptions\DataAccessException;
use App\Models\Playlist;

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
     * @param string $photoPath
     * @param int $userId
     * @throws DataAccessException
     * @return int playlistId
     */
    public function create(
        string $name,
        string $photoPath,
        int $userId
    ): int;

    /**
     * @param int $playlistId
     * @param string $name
     * @throws DataAccessException
     * @return void
     */
    public function update(int $playlistId, string $name): void;

    /**
     * @param int $playlistId
     * @throws DataAccessException
     * @return void
     */
    public function delete(int $playlistId): void;
}
