<?php

namespace App\Http\Requests\Album;

use App\Http\RequestModels\Album\CreateAlbumModel;
use App\Http\Requests\BaseFormRequest;
use Illuminate\Support\Facades\Log;

class CreateAlbumRequest extends BaseFormRequest
{
    public function rules(): array
    {
        return [
            'name' => 'required|string',
            'photo' => 'required|mimes:png',
            'genreName' => 'required|string',
            'publishTime' => 'nullable|string'
        ];
    }

    public function body(): CreateAlbumModel
    {
        $model = new CreateAlbumModel();
        $model->name = $this->string('name');
        $model->photo = $this->file('photo');
        $model->genreName = $this->string('genreName');
        $model->publishTime = $this->string('publishTime');

        return $model;
    }
}
