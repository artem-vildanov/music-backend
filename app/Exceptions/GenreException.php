<?php

namespace App\Exceptions;

class GenreException extends \Exception
{
    public static function notFound(string $genreName): self
    {
        return new self("genre with name = {$genreName} not found", 400);
    }
}
