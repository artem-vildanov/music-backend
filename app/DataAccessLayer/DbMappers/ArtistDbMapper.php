<?php

namespace App\DataAccessLayer\DbMappers;

use App\DataAccessLayer\DbModels\Artist;

class ArtistDbMapper
{
    /**
     * @param Artist[] $artists
     * @return Artist[]
     */
    public function mapMultipleDbArtists(array $artists): array
    {
        return array_map(fn ($artist) => $this->mapDbArtist($artist), $artists);
    }

    public function mapDbArtist(Artist $artist): Artist
    {
        $this->mapId($artist);
        $this->mapForeignKeys($artist);
        return $artist;
    }

    private function mapId(Artist $artist): void
    {
        $artist->id = $artist->_id;
        $artist->_id = null;
    }

    private function mapForeignKeys(Artist $artist): void
    {
        $artist->userId = (string)$artist->userId;
    }
}
