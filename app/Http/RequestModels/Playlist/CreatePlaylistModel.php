<?php

namespace App\Http\RequestModels\Playlist;

use Illuminate\Http\UploadedFile;

class CreatePlaylistModel
{
    public string $name;
    public ?UploadedFile $photo;
}
