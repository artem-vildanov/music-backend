<?php

declare(strict_types = 1);

namespace App\DataAccessLayer\DbMappers;

use App\DataAccessLayer\DbModels\Album;
use App\DataAccessLayer\DbModels\Artist;
use App\DataAccessLayer\DbModels\Song;
use MongoDB\Driver\Cursor;

/**
 * for mapping mongodb raw responses
 */
class QueryMapper
{
    public function __construct(
        private readonly AlbumDbMapper $albumDbMapper,
        private readonly ArtistDbMapper $artistDbMapper,
        private readonly SongDbMapper $songDbMapper,
    ) {}

    /**
     * @return Album[]
     */
    public function mapMultipleAlbums(Cursor $mongoResponse): array {
        /** формируем массив моделей */
        $unformattedFavourites = array_map(
            fn ($associatedAlbum) => new Album($associatedAlbum),
            $this->mapToAssociated($mongoResponse)
        );

        /** нужно дополнительно отформатировать id и внешние ключи */
        return $this->albumDbMapper->mapMultipleDbAlbums($unformattedFavourites);
    }

    /**
     * @return Artist[]
     */
    public function mapMultipleArtists(Cursor $mongoResponse): array {
        /** формируем массив моделей */
        $unformattedFavourites = array_map(
            fn ($associatedArtist) => new Artist($associatedArtist),
            $this->mapToAssociated($mongoResponse)
        );

        /** нужно дополнительно отформатировать id и внешние ключи */
        return $this->artistDbMapper->mapMultipleDbArtists($unformattedFavourites);
    }

    /**
     * @return Song[]
     */
    public function mapMultipleSongs(Cursor $mongoResponse): array {
        /** формируем массив моделей */
        $unformattedFavourites = array_map(
            fn ($associatedSong) => new Song($associatedSong),
            $this->mapToAssociated($mongoResponse)
        );

        /** нужно дополнительно отформатировать id и внешние ключи */
        return $this->songDbMapper->mapMultipleDbSongs($unformattedFavourites);
    }

    /**
     * @param Cursor $mongoResponse mongodb raw response
     * @return array objects from db in associated array form
     */
    private function mapToAssociated(Cursor $mongoResponse): array {
        $bsonDocumentsArray = $mongoResponse
            ->toArray()
            [0]['result']
            ->getArrayCopy();

//        dd($mongoResponse->toArray());

        return array_map(
            fn ($bsonDocument) => $bsonDocument->getArrayCopy(),
            $bsonDocumentsArray
        );
    }
}
