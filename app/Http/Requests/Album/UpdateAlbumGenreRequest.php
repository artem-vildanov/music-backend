<?php

namespace App\Http\Requests\Album;

use App\Http\Requests\BaseFormRequest;

class UpdateAlbumGenreRequest extends BaseFormRequest
{
    public function rules(): array
    {
        return [
            'genre' => 'required|string',
        ];
    }

    public function body(): string
    {
        return $this->string('genre');
    }
}
