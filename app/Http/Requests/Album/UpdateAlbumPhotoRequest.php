<?php

namespace App\Http\Requests\Album;

use App\Http\RequestModels\Album\UpdateAlbumNameAndGenreModel;
use App\Http\RequestModels\Album\UpdateAlbumPhotoModel;
use App\Http\RequestModels\Album\UpdateAlbumPublishTimeModel;
use App\Http\Requests\BaseFormRequest;

class UpdateAlbumPhotoRequest extends BaseFormRequest
{
    public function rules(): array
    {
        return [
            'photo' => 'required|mimes:png',
        ];
    }

    public function body(): UpdateAlbumPhotoModel
    {
        $model = new UpdateAlbumPhotoModel();
        $model->photo = $this->file('photo');

        return $model;
    }
}