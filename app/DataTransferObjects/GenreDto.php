<?php

namespace App\DataTransferObjects;

use App\Models\Genre;

class GenreDto
{
    public int $id;
    public string $name;
    public int $likes;
    public bool $isFavourite;
}
