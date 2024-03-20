<?php

namespace App\Repository;

use App\Exceptions\PlaylistSongsException;
use Illuminate\Support\Facades\DB;

class PlaylistSongsRepository implements Interfaces\IPlaylistSongsRepository
{
    public function checkSongInPlaylist(int $songId, int $playlistId): bool
    {
        return (bool)DB::table('songs_playlists')
            ->where([
                'song_id' => $songId,
                'playlist_id' => $playlistId
            ])
            ->first();
    }

    /**
     * @inheritDoc
     */
    public function getSongsIdsContainedInPlaylist(int $playlistId): array
    {
        return DB::table('songs_playlists')
            ->where('playlist_id', $playlistId)
            ->pluck('song_id')
            ->toArray();
    }

    /**
     * @inheritDoc
     */
    public function getUserPlaylistsIdsWithSong(int $songId, int $userId): array
    {
        $userPlaylistsIds = DB::table('playlists')
            ->where('user_id', $userId)
            ->pluck('id')
            ->toArray();

        return DB::table('songs_playlists')
            ->whereIn('playlist_id', $userPlaylistsIds)
            ->where('song_id', $songId)
            ->pluck('playlist_id')
            ->toArray();
    }

    /**
     * @throws PlaylistSongsException
     */
    public function addSongToPlaylist(int $songId, int $playlistId): void
    {
        $result = DB::table('songs_playlists')->insert([
            'playlist_id' => $playlistId,
            'song_id' => $songId
        ]);

        if (!$result) {
            throw PlaylistSongsException::failedAddSongToPlaylist($songId, $playlistId);
        }
    }

    /**
     * @throws PlaylistSongsException
     */
    public function deleteSongFromPlaylist(int $songId, int $playlistId): void
    {
        try {
            $result = DB::table('songs_playlists')->where([
                'playlist_id' => $playlistId,
                'song_id' => $songId
            ])->delete();
        } catch (\Exception $e) {
            throw PlaylistSongsException::failedDeleteSongFromPlaylist($songId, $playlistId);
        }

        if (!$result) {
            throw PlaylistSongsException::failedDeleteSongFromPlaylist($songId, $playlistId);
        }
    }
}
