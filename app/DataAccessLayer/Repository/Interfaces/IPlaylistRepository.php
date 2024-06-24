<?php

namespace App\DataAccessLayer\Repository\Interfaces;

use App\DataAccessLayer\DbModels\Playlist;
use App\Exceptions\DataAccessExceptions\DataAccessException;

interface IPlaylistRepository
{
    /**
     * @param string $playlistId
     * @throws DataAccessException
     * @return Playlist
     */
    public function getById(string $playlistId): Playlist;

    /**
     * @param string[] $playlistsIds
     * @return Playlist[]
     */
    public function getMultipleByIds(array $playlistsIds): array;

    /**
     * @param string $userId
     * @return Playlist[]
     */
    public function getPlaylistsByUserId(string $userId): array;

    /**
     * @param string $playlistId
     * @return string[] songs ids that contained in playlist
     */
    public function getSongsInPlaylist(string $playlistId): array;

    /**
     * @param string $name
     * @param string $userId
     * @throws DataAccessException
     * @return string playlistId
     */
    public function create(string $name, string $userId): string;

    /**
     * @param string $playlistId
     * @param string $name
     * @throws DataAccessException
     * @return void
     */
    public function updateName(string $playlistId, string $name): void;
    public function updatePhoto(string $playlistId, string $photoPath): void;

    public function addSongToPlaylist(string $playlistId, string $songId): void;
    public function removeSongFromPlaylist(string $playlistId, string $songId): void;
    public function removeSongFromAllPlaylists(string $songId): void;

    /**
     * @param string $playlistId
     * @throws DataAccessException
     * @return void
     */
    public function delete(string $playlistId): void;
}
