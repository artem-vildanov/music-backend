<?php

namespace App\Exceptions\DataAccessExceptions;

class PlaylistException extends DataAccessException
{

    public static function notFound(string $id): DataAccessException
    {
        return new self("playlist with id = {$id} not found", 400);
    }

    public static function failedToDelete(string $id): DataAccessException
    {
        return new self("failed to delete playlist with id = {$id}", 400);
    }

    public static function failedToCreate(): DataAccessException
    {
        return new self("failed to create playlist", 400);
    }

    public static function failedToUpdate(string $id): DataAccessException
    {
        return new self("failed to update playlist with id = {$id}", 400);
    }
}
