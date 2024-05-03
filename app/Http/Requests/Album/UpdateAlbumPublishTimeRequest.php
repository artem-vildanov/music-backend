<?php

namespace App\Http\Requests\Album;

use App\Http\RequestModels\Album\UpdateAlbumNameAndGenreModel;
use App\Http\RequestModels\Album\UpdateAlbumPublishTimeModel;
use App\Http\Requests\BaseFormRequest;

class UpdateAlbumPublishTimeRequest extends BaseFormRequest
{
    public function rules(): array
    {
        return [
            'publishTime' => 'required|string',
        ];
    }

    public function body(): UpdateAlbumPublishTimeModel
    {
        $model = new UpdateAlbumPublishTimeModel();
        $model->publishTime = $this->string('publishTime');

        return $model;
    }
}