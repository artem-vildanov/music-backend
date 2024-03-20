<?php

namespace App\Exceptions\FavouritesExceptions;

use Exception;

abstract class FavouritesException extends Exception
{
    protected abstract static function failedAddToFavourites(int $id): FavouritesException;
    protected abstract static function failedDeleteFromFavourites(int $id): FavouritesException;
    protected abstract static function failedIncrementLikes(int $id): FavouritesException;
    protected abstract static function failedDecrementLikes(int $id): FavouritesException;
}
