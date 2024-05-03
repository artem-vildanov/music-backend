<?php

namespace App\Http\Requests\Song;

use App\Http\RequestModels\Song\UpdateSongNameModel;
use App\Http\Requests\BaseFormRequest;

class UpdateSongNameRequest extends BaseFormRequest
{
    public function rules(): array
    {
        return [
            'name' => 'required|string',
            // 'music' => 'nullable|mimes:mp3',
        ];
    }

    /**
     * @return UpdateSongNameModel
     */
    public function body(): UpdateSongNameModel
    {
        $model = new UpdateSongNameModel();
        $model->name = $this->string('name');

        return $model;
    }
}
