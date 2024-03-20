<?php

namespace App\Mappers;

use App\Models\TokenPayloadModel;
use App\Models\User;
use Carbon\Carbon;
use stdClass;

class TokenPayloadMapper
{
//    private int $timeToLive;
//    private int $timeToRefresh;

//    public function __construct()
//    {
//        $this->timeToLive = (int)config('jwt.ttl');
//        $this->timeToRefresh = (int)config('jwt.ttr');
//    }

//    public function makeAuthJwtModel(User $user): TokenPayloadModel
//    {
//        $model = new TokenPayloadModel();
//        $model->id = $user->id;
//        $model->email = $user->email;
//        $model->name = $user->name;
//        $model->role = $user->role;
//        $model->createdAt = Carbon::now()->getTimestamp();
//        $model->refreshableUntil = Carbon::now()->addSeconds($this->timeToRefresh)->getTimestamp();
//        $model->expiredAt = Carbon::now()->addSeconds($this->timeToLive)->getTimestamp();
//
//        return $model;
//    }

    public function mapAuthJwtModelFromStdClass(stdClass $payload): TokenPayloadModel
    {
        $authModel = new TokenPayloadModel();
        $authModel->id = $payload->id;
        $authModel->name = $payload->name;
        $authModel->email = $payload->email;
        $authModel->artistId = $payload->artistId;
        $authModel->expiredAt = $payload->expiredAt;
        $authModel->refreshableUntil = $payload->refreshableUntil;
        $authModel->createdAt = $payload->createdAt;

        return $authModel;
    }
}
