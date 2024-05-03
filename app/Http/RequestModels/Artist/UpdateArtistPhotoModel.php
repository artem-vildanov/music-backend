<?php

namespace App\Http\RequestModels\Artist;

use Illuminate\Http\UploadedFile;

class UpdateArtistPhotoModel
{
    public UploadedFile $photo;
}