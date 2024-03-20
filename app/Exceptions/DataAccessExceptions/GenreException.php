<?php

namespace App\Exceptions\DataAccessExceptions;

class GenreException extends DataAccessException
{

    public static function notFound(int $id): DataAccessException
    {
        return new self("genre with id = {$id} not found", 400);
    }

    public static function failedToDelete(int $id): DataAccessException
    {
        return new self("failed to delete genre with id = {$id}", 400);
    }

    public static function failedToCreate(): DataAccessException
    {
        return new self("failed to create genre", 400);
    }

    public static function failedToUpdate(int $id): DataAccessException
    {
        return new self("failed to update genre with id = {$id}", 400);
    }
}
