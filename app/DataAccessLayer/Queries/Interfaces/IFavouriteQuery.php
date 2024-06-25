<?php

namespace App\DataAccessLayer\Queries\Interfaces;

use App\DataAccessLayer\DbModels\Album;
use App\DataAccessLayer\DbModels\Artist;
use App\DataAccessLayer\DbModels\Song;

interface IFavouriteQuery
{
    /**
     * @return Album[]
     */
    public function getFavouriteAlbums(string $userId): array;

    /**
     * @return Artist[]
     */
    public function getFavouriteArtists(string $userId): array;

    /**
     * @return Song[]
     */
    public function getFavouriteSongs(string $userId): array;
}
