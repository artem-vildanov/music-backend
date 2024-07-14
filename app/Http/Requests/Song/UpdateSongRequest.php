<?php

namespace App\Http\Requests\Song;

use App\Http\RequestModels\Song\UpdateSongModel;
use App\Http\Requests\BaseFormRequest;

class UpdateSongRequest extends BaseFormRequest
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
    public function body(): UpdateSongModel
    {
        return new UpdateSongModel(
            name: $this->string('name'),
            audioId: $this->string('audioId')
        );
    }
}
