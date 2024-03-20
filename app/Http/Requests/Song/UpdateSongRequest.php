<?php

namespace App\Http\Requests\Song;

use App\Http\RequestModels\Song\CreateSongModel;
use App\Http\RequestModels\Song\UpdateSongModel;
use App\Http\Requests\BaseFormRequest;

class UpdateSongRequest extends BaseFormRequest
{
    public function rules(): array
    {
        return [
            'name' => 'nullable|string',
            'music' => 'nullable|mimes:mp3',
        ];
    }

    /**
     * @return mixed
     */
    public function body(): UpdateSongModel
    {
        $model = new UpdateSongModel();
        $model->name = $this->string('name');
        $model->music = $this->file('music');

        return $model;
    }
}
