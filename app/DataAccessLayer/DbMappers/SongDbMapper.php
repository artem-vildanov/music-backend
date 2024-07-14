<?php

namespace App\DataAccessLayer\DbMappers;

use App\DataAccessLayer\DbModels\Song;

class SongDbMapper
{
    /**
     * @param Song[] $songs
     * @return Song[]
     */
    public function mapMultipleDbSongs(array $songs): array
    {
        return array_map(fn ($song) => $this->mapDbSong($song), $songs);
    }

    public function mapDbSong(Song $song): Song
    {
        $this->mapId($song);
        $this->mapForeignKeys($song);
        return $song;
    }

    private function mapId(Song $song): void
    {
        $song->id = $song->_id;
        $song->_id = null;
    }

    private function mapForeignKeys(Song $song): void
    {
        $song->artistId = (string)$song->artistId;
        $song->albumId = (string)$song->albumId;
        $song->audioId = (string)$song->audioId;
    }
}
