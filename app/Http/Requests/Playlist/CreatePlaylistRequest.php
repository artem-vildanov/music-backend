<?php

namespace App\Http\Requests\Playlist;

use App\Http\RequestModels\Artist\CreateArtistModel;
use App\Http\RequestModels\Playlist\CreatePlaylistModel;
use App\Http\Requests\BaseFormRequest;

class CreatePlaylistRequest extends BaseFormRequest
{
    public function rules(): array
    {
        return [
            'name' => 'required|string',
        ];
    }

    /**
     * @return mixed
     */
    public function body(): CreatePlaylistModel
    {
        $model = new CreatePlaylistModel();
        $model->name = $this->string('name');

        return $model;
    }
}
