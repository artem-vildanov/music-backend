<?php

namespace App\Http\RequestModels\Song;

use Illuminate\Http\UploadedFile;

class UpdateSongAudioModel
{
    public UploadedFile $audio;
}