<?php

declare(strict_types=1);

namespace App\Services\JwtServices;

use App\DataAccessLayer\DbModels\TokenPayloadModel;
use App\DtoLayer\DtoMappers\TokenPayloadMapper;
use App\Exceptions\JwtException;
use Exception;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;


class EncodeDecodeService
{
    private string $authJwtKey;
    private string $algo;

    public function __construct(
        private readonly TokenPayloadMapper  $tokenPayloadMapper,
    )
    {
        $this->authJwtKey = config('jwt.key');
        $this->algo = config('jwt.algorithm');
    }

    public function encode(TokenPayloadModel $model): string
    {
        return JWT::encode((array)$model, $this->authJwtKey, $this->algo);
    }

    /**
     * @throws JwtException
     */
    public function decode(string $token): TokenPayloadModel
    {
        $decodeKey = new Key($this->authJwtKey, $this->algo);
        try {
            $payloadStdClass = JWT::decode($token, $decodeKey);
        } catch (Exception $exception) {
            throw JwtException::invalidToken();
        }

        return $this->tokenPayloadMapper->mapTokenPayload($payloadStdClass);
    }
}
