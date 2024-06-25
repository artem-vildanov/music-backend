<?php

namespace App\DataAccessLayer\DbMappers;

use App\DataAccessLayer\DbModels\Playlist;
use MongoDB\BSON\ObjectId;

class PlaylistDbMapper
{
    /**
     * @param Playlist[] $playlists
     * @return Playlist[]
     */
    public function mapMultipleDbPlaylists(array $playlists): array
    {
        return array_map(fn ($playlist) => $this->mapDbPlaylist($playlist), $playlists);
    }

    public function mapDbPlaylist(Playlist $playlist): Playlist
    {
        $this->mapId($playlist);
        $this->mapForeignKeys($playlist);
        return $playlist;
    }

    private function mapId(Playlist $playlist): void
    {
        $playlist->id = $playlist->_id;
        $playlist->_id = null;
    }

    private function mapForeignKeys(Playlist $playlist): void
    {
        $playlist->songsIds = array_map(
            fn ($objectId) => (string)$objectId,
            $playlist->songsIds ?? []
        );
        $playlist->userId = (string)$playlist->userId;
    }
}
