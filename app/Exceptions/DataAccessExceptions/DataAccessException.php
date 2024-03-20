<?php

namespace App\Exceptions\DataAccessExceptions;

use Exception;

abstract class DataAccessException extends Exception
{
    protected static abstract function notFound(int $id): DataAccessException;
    protected static abstract function failedToDelete(int $id): DataAccessException;
    protected static abstract function failedToCreate(): DataAccessException;
    protected static abstract function failedToUpdate(int $id): DataAccessException;
}
