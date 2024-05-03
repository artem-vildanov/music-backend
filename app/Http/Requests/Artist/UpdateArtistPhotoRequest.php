<?php

namespace App\Http\Requests\Artist;

use App\Http\RequestModels\Artist\UpdateArtistPhotoModel;
use App\Http\Requests\BaseFormRequest;

class UpdateArtistPhotoRequest extends BaseFormRequest
{
    public function rules(): array
    {
        return [
            'photo' => 'nullable|mimes:png',
        ];
    }

    public function body(): UpdateArtistPhotoModel
    {
        $model = new UpdateArtistPhotoModel();
        $model->photo = $this->file('photo');

        return $model;
    }
}