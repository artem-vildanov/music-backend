<?php

namespace App\Http\Requests\File;

use App\Http\Requests\BaseFormRequest;
use Illuminate\Http\UploadedFile;

class UploadAudioRequest extends BaseFormRequest
{
    public function rules(): array
    {
        return [
            'audio' => 'required|mimes:mp3',
        ];
    }

    /**
     * @return mixed
     */
    public function body(): UploadedFile
    {
        return $this->file('audio');
    }
}
