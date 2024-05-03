<?php

namespace App\Http\Requests\Playlist;

use App\Http\RequestModels\Playlist\UpdatePlaylistPhotoModel;
use App\Http\Requests\BaseFormRequest;

class UpdatePlaylistPhotoRequest extends BaseFormRequest
{
    public function rules(): array
    {
        return [
            'photo' => 'required|mimes:png',
        ];
    }

    /**
     * @return UpdatePlaylistPhotoModel
     */
    public function body(): UpdatePlaylistPhotoModel
    {
        $model = new UpdatePlaylistPhotoModel();
        $model->photo = $this->file('photo');

        return $model;
    }
}
