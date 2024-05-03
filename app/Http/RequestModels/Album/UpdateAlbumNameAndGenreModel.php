<?php

namespace App\Http\RequestModels\Album;

use Illuminate\Http\UploadedFile;

class UpdateAlbumNameAndGenreModel
{
    public string $name;
    public int $genreId;
}
