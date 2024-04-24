<?php

namespace App\Http\Requests\Album;

use App\Http\RequestModels\Album\CreateAlbumModel;
use App\Http\RequestModels\Album\UpdateAlbumModel;
use App\Http\Requests\BaseFormRequest;

class UpdateAlbumRequest extends BaseFormRequest
{
    public function rules(): array
    {
        return [
            'name' => 'nullable|string',
            'photo' => 'nullable|mimes:png',
            'status' => 'nullable|string|in:private,public',
            'genreId' => 'nullable|integer',
            'publishTime' => 'nullable|string'
        ];
    }

    public function body(): UpdateAlbumModel
    {
        $model = new UpdateAlbumModel();
        $model->name = $this->string('name');
        $model->photo = $this->file('photo');
        $model->status = $this->string('status');
        $model->genreId = $this->integer('genreId');
        $model->publishTime = $this->string('publishTime');

        return $model;
    }
}
