<?php

namespace App\Http\Requests\File;

class UploadPhotoRequest
{
    public function rules(): array
    {
        return [
            'photo' => 'required|mimes:png',
        ];
    }

    /**
     * @return mixed
     */
    public function body(): UploadedFile
    {
        return $this->file('photo');
    }
}
