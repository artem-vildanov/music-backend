<?php

namespace App\Exceptions\DataAccessExceptions;

class AlbumException extends DataAccessException
{

    public static function notFound(string $id): DataAccessException
    {
        return new self("album with id = {$id} not found", 400);
    }

    public static function failedToDelete(string $id): DataAccessException
    {
        return new self("failed to delete album with id = {$id}", 400);
    }

    public static function failedToCreate(): DataAccessException
    {
        return new self("failed to create album", 400);
    }

    public static function failedToUpdate(string $id): DataAccessException
    {
        return new self("failed to update album with id = {$id}", 400);
    }
}
