<?php

namespace App\Http\Requests\Song;

use App\Http\Requests\BaseFormRequest;
use Illuminate\Http\UploadedFile;

class UpdateSongAudioRequest extends BaseFormRequest
{
    public function rules(): array
    {
        return [
            'audio' => 'required|mimes:mp3',
        ];
    }

    public function body(): UploadedFile
    {
        return $this->file('audio');
    }
}
