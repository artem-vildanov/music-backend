<?php

namespace App\Utils\UtilModels;

use stdClass;

class TokenPayloadMapper
{
    public function mapTokenPayload(stdClass $payload): TokenPayloadModel
    {
        return new TokenPayloadModel(
            id: $payload->id,
            name: $payload->name,
            email: $payload->email,
            artistId: $payload->artistId,
            createdAt: $payload->createdAt,
            refreshableUntil: $payload->refreshableUntil,
            expiredAt: $payload->expiredAt,
        );
    }
}
