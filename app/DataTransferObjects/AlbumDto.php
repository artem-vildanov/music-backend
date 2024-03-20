<?php

namespace App\DataTransferObjects;

use App\Models\Album;
use App\Models\Artist;
use App\Models\Genre;
use App\Models\Song;
use Illuminate\Http\UploadedFile;

class AlbumDto
{
    public int $id;
    public string $name;
    public string $photoPath;
    public int $likes;
    public int $artistId;
    public string $artistName;
    public int $genreId;
    public string $genreName;
    public bool $isFavourite;
}
