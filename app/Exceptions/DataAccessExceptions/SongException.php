<?php

namespace App\Exceptions\DataAccessExceptions;

class SongException extends DataAccessException
{

    public static function notFound(string $id): DataAccessException
    {
        return new self("song with id = {$id} not found", 400);
    }

    public static function failedToDelete(string $id): DataAccessException
    {
        return new self("failed to delete song with id = {$id}", 400);
    }

    public static function failedToCreate(): DataAccessException
    {
        return new self("failed to create song", 400);
    }

    public static function failedToUpdate(string $id): DataAccessException
    {
        return new self("failed to update song with id = {$id}", 400);
    }
}
