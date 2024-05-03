<?php

namespace App\Http\Requests\Playlist;

use App\Http\RequestModels\Playlist\UpdatePlaylistNameModel;
use App\Http\Requests\BaseFormRequest;

class UpdatePlaylistNameRequest extends BaseFormRequest
{
    public function rules(): array
    {
        return [
            'name' => 'required|string',
        ];
    }

    /**
     * @return UpdatePlaylistNameModel
     */
    public function body(): UpdatePlaylistNameModel
    {
        $model = new UpdatePlaylistNameModel();
        $model->name = $this->string('name');

        return $model;
    }
}
