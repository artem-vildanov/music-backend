<?php

namespace App\Http\Requests\Artist;

use App\Http\RequestModels\Artist\CreateArtistModel;
use App\Http\Requests\BaseFormRequest;

class CreateArtistRequest extends BaseFormRequest
{
    public function rules(): array
    {
        return [
            'name' => 'required|string',
            'photo' => 'required|mimes:png',
        ];
    }

    /**
     * @return mixed
     */
    public function body(): CreateArtistModel
    {
        $model = new CreateArtistModel();
        $model->name = $this->string('name');
        $model->photo = $this->file('photo');

        return $model;
    }
}
