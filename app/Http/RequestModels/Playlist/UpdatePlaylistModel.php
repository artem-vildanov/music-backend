<?php

namespace App\Http\RequestModels\Playlist;

use Illuminate\Http\UploadedFile;

class UpdatePlaylistModel
{
    public ?string $name;
    public ?UploadedFile $photo;
}
