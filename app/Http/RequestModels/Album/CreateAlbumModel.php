<?php

namespace App\Http\RequestModels\Album;

use Illuminate\Http\UploadedFile;

class CreateAlbumModel
{
    public string $name;
    public UploadedFile $photo;
    public string $genreName;
    public ?string $publishTime;
}
