<?php

namespace App\Http\Requests\Song;

use App\Http\RequestModels\Song\CreateSongModel;
use App\Http\Requests\BaseFormRequest;

class CreateSongRequest extends BaseFormRequest
{
    public function rules(): array
    {
        return [
            'name' => 'required|string',
            'music' => 'required|mimes:mp3',
        ];
    }

    /**
     * @return mixed
     */
    public function body(): CreateSongModel
    {
        $model = new CreateSongModel();
        $model->name = $this->string('name');
        $model->music = $this->file('music');

        return $model;
    }
}
