<?php

namespace App\Exceptions\DataAccessExceptions;

class ArtistException extends DataAccessException
{

    public static function notFoundByUserId(int $id): DataAccessException
    {
        return new self("user with id = {$id} doesnt have an artist account");
    }

    public static function notFound(int $id): DataAccessException
    {
        return new self("artist with id = {$id} not found", 400);
    }

    public static function failedToDelete(int $id): DataAccessException
    {
        return new self("failed to delete artist with id = {$id}", 400);
    }

    public static function failedToCreate(): DataAccessException
    {
        return new self("failed to create artist", 400);
    }

    public static function failedToUpdate(int $id): DataAccessException
    {
        return new self("failed to update artist with id = {$id}", 400);
    }
}
