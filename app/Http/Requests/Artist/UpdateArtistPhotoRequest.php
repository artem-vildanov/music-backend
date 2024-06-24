<?php

namespace App\Http\Requests\Artist;

use App\Http\RequestModels\Artist\UpdateArtistPhotoModel;
use App\Http\Requests\BaseFormRequest;
use Illuminate\Http\UploadedFile;

class UpdateArtistPhotoRequest extends BaseFormRequest
{
    public function rules(): array
    {
        return [
            'photo' => 'nullable|mimes:png',
        ];
    }

    public function body(): UploadedFile
    {
        return $this->file('photo');
    }
}
