<?php

namespace App\Exceptions\DataAccessExceptions;

class UserException extends DataAccessException
{
    public static function notFound(int $id): DataAccessException
    {
        return new self("user with id = {$id} not found", 400);
    }

    public static function notFoundByEmail(string $email): DataAccessException
    {
        return new self("user with email = {$email} not found", 400);
    }

    public static function failedToDelete(int $id): DataAccessException
    {
        return new self("failed to delete user with id = {$id}", 400);
    }

    public static function failedToCreate(): DataAccessException
    {
        return new self("failed to create user", 400);
    }

    public static function failedToUpdate(int $id): DataAccessException
    {
        return new self("failed to update user with id = {$id}", 400);
    }
}
