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
            'audioId' => 'required|string',
        ];
    }

    /**
     * @return mixed
     */
    public function body(): CreateSongModel
    {
        return new CreateSongModel(
            name: $this->string('name'),
            audioId: $this->string('audioId')
        );
    }
}
