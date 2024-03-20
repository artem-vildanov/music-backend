<?php

namespace App\Exceptions\FavouritesExceptions;

class FavouriteGenresException extends FavouritesException
{

    public static function failedAddToFavourites(int $id): FavouritesException
    {
        return new self("failed to add genre with id = {$id} to favourites", 400);
    }

    public static function failedDeleteFromFavourites(int $id): FavouritesException
    {
        return new self("failed to delete genre with id = {$id} from favourites", 400);
    }

    public static function failedIncrementLikes(int $id): FavouritesException
    {
        return new self("failed to increment likes of genre with id = {$id}", 400);
    }

    public static function failedDecrementLikes(int $id): FavouritesException
    {
        return new self("failed to decrement likes of genre with id = {$id}", 400);
    }
}
