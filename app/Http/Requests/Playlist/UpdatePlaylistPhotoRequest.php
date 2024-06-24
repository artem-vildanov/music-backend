<?php

namespace App\Http\Requests\Playlist;

use App\Http\RequestModels\Playlist\UpdatePlaylistPhotoModel;
use App\Http\Requests\BaseFormRequest;
use Illuminate\Http\UploadedFile;

class UpdatePlaylistPhotoRequest extends BaseFormRequest
{
    public function rules(): array
    {
        return [
            'photo' => 'required|mimes:png',
        ];
    }

    public function body(): UploadedFile
    {
        return $this->file('photo');
    }
}
