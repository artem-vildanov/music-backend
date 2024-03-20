<?php

namespace App\Exceptions;

class PlaylistSongsException extends \Exception
{
    public static function failedAddSongToPlaylist(int $songId, int $playlistId): PlaylistSongsException
    {
        return new self("failed to add song with id = {$songId} to playlist with id = {$playlistId}", 400);
    }

    public static function failedDeleteSongFromPlaylist(int $songId, int $playlistId): PlaylistSongsException
    {
        return new self("failed to delete song with id = {$songId} from playlist with id = {$playlistId}", 400);
    }
}
