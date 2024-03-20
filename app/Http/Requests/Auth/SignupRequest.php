<?php

namespace App\Http\Requests\Auth;

use App\Http\RequestModels\Auth\SignupRequestModel;
use App\Http\Requests\BaseFormRequest;

class SignupRequest extends BaseFormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string',
            'email' => 'required|string',
            'password' => 'required|string'
        ];
    }

    public function body(): SignupRequestModel
    {
        $model = new SignupRequestModel();
        $model->name = $this->string('name');
        $model->email = $this->string('email');
        $model->password = $this->string('password');

        return $model;
    }
}
