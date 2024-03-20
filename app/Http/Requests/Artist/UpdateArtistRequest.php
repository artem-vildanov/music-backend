<?php

namespace App\Http\Requests\Artist;

use App\Http\RequestModels\Artist\UpdateArtistModel;
use App\Http\Requests\BaseFormRequest;

class UpdateArtistRequest extends BaseFormRequest
{
    public function rules(): array
    {
        return [
            'name' => 'nullable|string',
            'photo' => 'nullable|mimes:png',
        ];
    }

    /**
     * @return mixed
     */
    public function body(): UpdateArtistModel
    {
        $model = new UpdateArtistModel();
        $model->name = $this->string('name');
        $model->photo = $this->file('photo');

        return $model;
    }
}
