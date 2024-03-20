<?php

namespace App\DataTransferObjects;

use App\Models\Album;
use App\Models\Artist;
use Illuminate\Http\UploadedFile;

class ArtistDto
{
    public int $id;
    public string $name;
    public string $photoPath;
    public int $likes;
    public int $userId;
    public bool $isFavourite;
}
