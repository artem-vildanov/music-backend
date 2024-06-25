<?php

namespace App\Exceptions\DataAccessExceptions;

class UserException extends DataAccessException
{
    public static function emailExists(string $email) : DataAccessException
    {
        return new self("User with email '{$email}' already exists", 400);
    }
    public static function notFound(string $id): DataAccessException
    {
        return new self("user with id = {$id} not found", 400);
    }

    public static function notFoundByEmail(string $email): DataAccessException
    {
        return new self("user with email = {$email} not found", 400);
    }

    public static function failedToDelete(string $id): DataAccessException
    {
        return new self("failed to delete user with id = {$id}", 400);
    }

    public static function failedToCreate(): DataAccessException
    {
        return new self("failed to create user", 400);
    }

    public static function failedToUpdate(string $id): DataAccessException
    {
        return new self("failed to update user with id = {$id}", 400);
    }
}
