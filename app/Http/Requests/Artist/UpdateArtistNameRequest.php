<?php

namespace App\Http\Requests\Artist;

use App\Http\RequestModels\Artist\UpdateArtistNameModel;
use App\Http\Requests\BaseFormRequest;

class UpdateArtistNameRequest extends BaseFormRequest
{
    public function rules(): array
    {
        return [
            'name' => 'required|string',
        ];
    }

    /**
     * @return UpdateArtistNameModel
     */
    public function body(): UpdateArtistNameModel
    {
        $model = new UpdateArtistNameModel();
        $model->name = $this->string('name');
        
        return $model;
    }
}
