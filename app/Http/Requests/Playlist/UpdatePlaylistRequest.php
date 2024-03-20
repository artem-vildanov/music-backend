<?php

namespace App\Http\Requests\Playlist;

use App\Http\RequestModels\Artist\UpdateArtistModel;
use App\Http\RequestModels\Playlist\UpdatePlaylistModel;
use App\Http\Requests\BaseFormRequest;

class UpdatePlaylistRequest extends BaseFormRequest
{
    public function rules(): array
    {
        return [
            'name' => 'nullable|string',
            'photo' => 'nullable|mimes:png',
        ];
    }

    /**
     * @return mixed
     */
    public function body(): UpdatePlaylistModel
    {
        $model = new UpdatePlaylistModel();
        $model->name = $this->string('name');
        $model->photo = $this->file('photo');

        return $model;
    }
}
