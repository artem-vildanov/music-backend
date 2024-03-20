<?php

namespace App\Http\RequestModels\Song;

use Illuminate\Http\UploadedFile;

class CreateSongModel
{
    public string $name;
    public UploadedFile $music;
}
