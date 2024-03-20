<?php

namespace App\Http\RequestModels\Artist;

use Illuminate\Http\UploadedFile;

class UpdateArtistModel
{
    public ?string $name;
    public ?UploadedFile $photo;
}
