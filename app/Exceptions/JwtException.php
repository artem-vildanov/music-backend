<?php

namespace App\Exceptions;

use App\Models\TokenPayloadModel;

class JwtException extends \Exception
{
    protected ?TokenPayloadModel $tokenPayload;
    public function __construct(string $message = "", TokenPayloadModel $tokenPayload = null, int $code = 0, ?Throwable $previous = null)
    {
        $this->tokenPayload = $tokenPayload;
        parent::__construct($message, $code, $previous);
    }

    public function getTokenPayload(): TokenPayloadModel
    {
        return $this->tokenPayload;
    }

    public static function tokenExpired(TokenPayloadModel $tokenPayload): JwtException
    {
        return new self('token expired', $tokenPayload, 403);
    }

    public static function tokenExpiredNotRefreshable(): JwtException
    {
        return new self('token expired, not refreshable', null, 403);
    }

    public static function invalidToken(): JwtException
    {
        return new self('invalid token', null, 403);
    }

    public static function noTokenProvided(): JwtException
    {
        return new self('no token provided in request header', null, 403);
    }
}
