<?php

namespace App\Exceptions\DataAccessExceptions;

class PlaylistException extends DataAccessException
{

    public static function notFound(int $id): DataAccessException
    {
        return new self("playlist with id = {$id} not found", 400);
    }

    public static function failedToDelete(int $id): DataAccessException
    {
        return new self("failed to delete playlist with id = {$id}", 400);
    }

    public static function failedToCreate(): DataAccessException
    {
        return new self("failed to create playlist", 400);
    }

    public static function failedToUpdate(int $id): DataAccessException
    {
        return new self("failed to update playlist with id = {$id}", 400);
    }
}
