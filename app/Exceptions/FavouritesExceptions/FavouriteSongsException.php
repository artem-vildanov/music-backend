<?php

namespace App\Exceptions\FavouritesExceptions;

class FavouriteSongsException extends FavouritesException
{

    public static function failedAddToFavourites(int $id): FavouritesException
    {
        return new self("failed to add song with id = {$id} to favourites", 400);
    }

    public static function failedDeleteFromFavourites(int $id): FavouritesException
    {
        return new self("failed to delete song with id = {$id} from favourites", 400);
    }

    public static function failedIncrementLikes(int $id): FavouritesException
    {
        return new self("failed to increment likes of song with id = {$id}", 400);
    }

    public static function failedDecrementLikes(int $id): FavouritesException
    {
        return new self("failed to decrement likes of song with id = {$id}", 400);
    }
}
