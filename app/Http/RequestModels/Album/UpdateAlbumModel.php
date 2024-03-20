<?php

namespace App\Http\RequestModels\Album;

use Illuminate\Http\UploadedFile;

class UpdateAlbumModel
{
    public ?string $name;
    public ?UploadedFile $photo;
    public ?string $status;
    public ?int $genreId;
}
