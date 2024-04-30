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
            'genreId' => 'required|int',
            'publishTime' => 'nullable|string'
        ];
    }

    public function body(): CreateAlbumModel
    {
        $model = new CreateAlbumModel();
        $model->name = $this->string('name');
        $model->photo = $this->file('photo');
        $model->genreId = $this->integer('genreId');
        $model->publishTime = $this->string('publishTime');

        return $model;
    }
}
