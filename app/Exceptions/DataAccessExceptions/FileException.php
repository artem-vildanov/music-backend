<?php

namespace App\Exceptions\DataAccessExceptions;

class FileException extends DataAccessException
{
    public static function notFound(string $id): DataAccessException
    {
        return new self("file with id = {$id} not found", 404);
    }

    public static function failedToDelete(string $id): DataAccessException
    {
        return new self("failed to delete file with id = {$id}", 400);
    }

    public static function failedToCreate(): DataAccessException
    {
        return new self("failed to create file", 400);
    }

    public static function failedToUpdate(string $id): DataAccessException
    {
    }
}
