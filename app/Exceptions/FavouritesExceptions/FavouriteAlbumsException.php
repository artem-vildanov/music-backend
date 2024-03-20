<?php

namespace App\Exceptions\FavouritesExceptions;

class FavouriteAlbumsException extends \App\Exceptions\FavouritesExceptions\FavouritesException
{
    public static function failedAddToFavourites(int $id): FavouritesException
    {
        return new self("failed to add album with id = {$id} to favourites", 400);
    }

    public static function failedDeleteFromFavourites(int $id): FavouritesException
    {
        return new self("failed to delete album with id = {$id} from favourites", 400);
    }

    public static function failedIncrementLikes(int $id): FavouritesException
    {
        return new self("failed to increment likes of album with id = {$id}", 400);
    }

    public static function failedDecrementLikes(int $id): FavouritesException
    {
        return new self("failed to decrement likes of album with id = {$id}", 400);
    }
}
