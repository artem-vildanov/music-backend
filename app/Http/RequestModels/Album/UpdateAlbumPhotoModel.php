<?php

namespace App\Http\RequestModels\Album;

use Illuminate\Http\UploadedFile;

class UpdateAlbumPhotoModel
{
    public UploadedFile $photo;
}