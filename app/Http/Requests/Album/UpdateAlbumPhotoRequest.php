<?php

declare(strict_types = 1);

namespace App\Http\Requests\Album;

use App\Http\RequestModels\Album\UpdateAlbumNameAndGenreModel;
use App\Http\RequestModels\Album\UpdateAlbumPhotoModel;
use App\Http\RequestModels\Album\UpdateAlbumPublishTimeModel;
use App\Http\Requests\BaseFormRequest;
use Illuminate\Http\UploadedFile;

class UpdateAlbumPhotoRequest extends BaseFormRequest
{
    public function rules(): array
    {
        return [
            'photo' => 'required|mimes:png',
        ];
    }

    public function body(): UploadedFile
    {
        return $this->file('photo');
    }
}
