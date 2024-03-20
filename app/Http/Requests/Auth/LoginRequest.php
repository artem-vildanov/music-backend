<?php

namespace App\Http\Requests\Auth;

use App\Http\RequestModels\Auth\LoginRequestModel;

class LoginRequest extends \App\Http\Requests\BaseFormRequest
{
    public function rules(): array
    {
        return [
            'email' => 'required|string',
            'password' => 'required|string'
        ];
    }

    public function body(): LoginRequestModel
    {
        $model = new LoginRequestModel();
        $model->email = $this->string('email');
        $model->password = $this->string('password');
        return $model;
    }
}
