<?php

namespace App\Http\Requests\Album;

use App\Http\Requests\BaseFormRequest;

class UpdateAlbumNameRequest extends BaseFormRequest
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
