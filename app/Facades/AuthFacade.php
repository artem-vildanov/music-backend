<?php

namespace App\Facades;

use App\DomainLayer\DomainModels\TokenPayloadModel;
use Illuminate\Support\Facades\Request;

class AuthFacade
{
    public static function getUserId(): string
    {
        return self::getAuthInfo()->id;
    }

    public static function getAuthInfo(): TokenPayloadModel
    {
        return Request::instance()->get('authInfo');
    }
}
