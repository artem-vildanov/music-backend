<?php

namespace App\Exceptions\DataAccessExceptions;

use Exception;

abstract class DataAccessException extends Exception
{
    protected static abstract function notFound(string $id): DataAccessException;
    protected static abstract function failedToDelete(string $id): DataAccessException;
    protected static abstract function failedToCreate(): DataAccessException;
    protected static abstract function failedToUpdate(string $id): DataAccessException;
}
