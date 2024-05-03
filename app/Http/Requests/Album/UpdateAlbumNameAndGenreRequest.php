<?php

namespace App\Http\Requests\Album;

use App\Http\RequestModels\Album\UpdateAlbumNameAndGenreModel;
use App\Http\Requests\BaseFormRequest;

class UpdateAlbumNameAndGenreRequest extends BaseFormRequest
{
    public function rules(): array
    {
        return [
            'name' => 'required|string',
            'genreId' => 'required|integer',
        ];
    }

    public function body(): UpdateAlbumNameAndGenreModel
    {
        $model = new UpdateAlbumNameAndGenreModel();
        $model->name = $this->string('name');
        $model->genreId = $this->integer('genreId');

        return $model;
    }
}
