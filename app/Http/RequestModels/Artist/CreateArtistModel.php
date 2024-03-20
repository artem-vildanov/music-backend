<?php

namespace App\Http\RequestModels\Artist;

use Illuminate\Http\UploadedFile;

class CreateArtistModel
{
    public string $name;
    public UploadedFile $photo;
}
