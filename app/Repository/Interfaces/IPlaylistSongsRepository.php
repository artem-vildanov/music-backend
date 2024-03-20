<?php

namespace App\Repository\Interfaces;

use App\Exceptions\PlaylistSongsException;

interface IPlaylistSongsRepository
{
    public function checkSongInPlaylist(int $songId, int $playlistId): bool;

    /**
     * @param int $playlistId
     * @return int[] all the songs that are contained in the playlist
     */
    public function getSongsIdsContainedInPlaylist(int $playlistId): array;

    /**
     * @param int $songId
     * @return int[] all playlists that contain the song with $songId
     */
    public function getUserPlaylistsIdsWithSong(int $songId, int $userId): array;

    /**
     * @param int $songId
     * @param int $playlistId
     * @throws PlaylistSongsException
     * @return void
     */
    public function addSongToPlaylist(int $songId, int $playlistId): void;

    /**
     * @param int $songId
     * @param int $playlistId
     * @throws PlaylistSongsException
     * @return void
     */
    public function deleteSongFromPlaylist(int $songId, int $playlistId): void;
}
