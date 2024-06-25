<?php

namespace App\DataAccessLayer\DbMappers;

use App\DataAccessLayer\DbModels\Album;

class AlbumDbMapper
{
    /**
     * @param array $albums
     * @return Album[]
     */
    public function mapMultipleDbAlbums(array $albums): array
    {
        return array_map(fn ($album) => $this->mapDbAlbum($album), $albums);
    }

    public function mapDbAlbum(Album $album): Album
    {
        $this->mapId($album);
        $this->mapForeignKeys($album);
        return $album;
    }

    private function mapId(Album $album): void
    {
        $album->id = $album->_id;
        $album->_id = null;
    }

    private function mapForeignKeys(Album $album): void
    {
        $album->artistId = (string)$album->artistId;
    }
}
