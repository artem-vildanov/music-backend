<?php

namespace App\Http\Requests\Song;

use App\Http\RequestModels\Song\UpdateSongAudioModel;
use App\Http\Requests\BaseFormRequest;

class UpdateSongAudioRequest extends BaseFormRequest
{
    public function rules(): array
    {
        return [
            'audio' => 'required|mimes:mp3',
        ];
    }

    /**
     * @return UpdateSongAudioModel
     */
    public function body(): UpdateSongAudioModel
    {
        $model = new UpdateSongAudioModel();
        $model->audio = $this->file('audio');

        return $model;
    }
}
