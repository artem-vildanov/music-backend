<?php

namespace App\Http\RequestModels\Song;

use Illuminate\Http\UploadedFile;

class UpdateSongModel
{
    public ?string $name;
    public ?UploadedFile $music;
}
