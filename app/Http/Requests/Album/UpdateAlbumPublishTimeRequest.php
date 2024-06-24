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

    public function body(): string
    {
        return $this->string('publishTime');
    }
}
