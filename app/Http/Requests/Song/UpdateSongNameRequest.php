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
        ];
    }

    public function body(): string
    {
        return $this->string('name');
    }
}
