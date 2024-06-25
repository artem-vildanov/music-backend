<?php

namespace App\Exceptions\DataAccessExceptions;

class ArtistException extends DataAccessException
{

    public static function notFoundByUserId( string $id): DataAccessException
    {
        return new self("user with id = {$id} doesnt have an artist account", 400);
    }

    public static function notFound( string $id): DataAccessException
    {
        return new self("artist with id = {$id} not found", 400);
    }

    public static function failedToDelete( string $id): DataAccessException
    {
        return new self("failed to delete artist with id = {$id}", 400);
    }

    public static function failedToCreate(): DataAccessException
    {
        return new self("failed to create artist", 400);
    }

    public static function failedToUpdate( string $id): DataAccessException
    {
        return new self("failed to update artist with id = {$id}", 400);
    }
}
