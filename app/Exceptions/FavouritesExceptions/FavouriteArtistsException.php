<?php

namespace App\Exceptions\FavouritesExceptions;

class FavouriteArtistsException extends \App\Exceptions\FavouritesExceptions\FavouritesException
{

    public static function failedAddToFavourites(int $id): FavouritesException
    {
        return new self("failed to add artist with id = {$id} to favourites", 400);
    }

    public static function failedDeleteFromFavourites(int $id): FavouritesException
    {
        return new self("failed to delete artist with id = {$id} from favourites", 400);
    }

    public static function failedIncrementLikes(int $id): FavouritesException
    {
        return new self("failed to increment likes of artist with id = {$id}", 400);
    }

    public static function failedDecrementLikes(int $id): FavouritesException
    {
        return new self("failed to decrement likes of artist with id = {$id}", 400);
    }
}
