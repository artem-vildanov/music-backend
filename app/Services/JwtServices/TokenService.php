<?php

namespace App\Services\JwtServices;

use App\Exceptions\DataAccessExceptions\DataAccessException;
use App\Exceptions\JwtException;
use App\Exceptions\RedisException;
use App\Models\TokenPayloadModel;
use App\Models\User;
use App\Repository\Interfaces\IArtistRepository;
use App\Repository\Interfaces\IUserRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;

class TokenService
{
    public function __construct(
        private readonly EncodeDecodeService $encodeDecodeService,
        private readonly TokenStorageService $tokenStorageService,
        private readonly IUserRepository     $userRepository,
        private readonly IArtistRepository   $artistRepository
    ) {}

    /**
     * @throws JwtException
     */
    public function getTokenFromRequest(Request $request): ?string
    {
        $header = $request->header('authorization');

        if ($header === null) {
            throw JwtException::noTokenProvided();
        }

        $parts = explode(' ', $header, 2);

        if (count($parts) < 2) {
            throw JwtException::noTokenProvided();
        }

        [$authHeader, $authToken] = $parts;

        if ($authHeader !== 'Bearer') {
            throw JwtException::noTokenProvided();
        }

        return $authToken;
    }

    public function createToken(User $user): string
    {
        $model = $this->createTokenPayload($user);
        $jwt = $this->encodeDecodeService->encode($model);

        $this->tokenStorageService->saveToken($jwt, $user->id);

        return $jwt;
    }

    private function createTokenPayload(User $user): TokenPayloadModel
    {
        $timeToLive = (int)config('jwt.ttl');
        $timeToRefresh = (int)config('jwt.ttr');

        $model = new TokenPayloadModel();
        $model->id = $user->id;
        $model->email = $user->email;
        $model->name = $user->name;

        try {
            $model->artistId = $this->artistRepository->getByUserId($user->id)->id;
        } catch (DataAccessException $e) {
            $model->artistId = null;
        }

        $model->createdAt = Carbon::now()->getTimestamp();
        $model->refreshableUntil = Carbon::now()->addSeconds($timeToRefresh)->getTimestamp();
        $model->expiredAt = Carbon::now()->addSeconds($timeToLive)->getTimestamp();

        return $model;
    }

    /**
     * @throws JwtException
     */
    public function getTokenPayload(string $token): TokenPayloadModel
    {
        try {
            $tokenPayload = $this->encodeDecodeService->decode($token);
            $this->tokenStorageService->findToken($token);
            $this->checkIfExpired($tokenPayload);
            $this->checkIfRefreshable($tokenPayload);
        } catch (RedisException $e) {
            throw JwtException::invalidToken();
        }

        return $tokenPayload;
    }

    /**
     * @throws JwtException
     */
    private function checkIfExpired(TokenPayloadModel $payload): void
    {
        $currentTime = Carbon::now()->getTimestamp();
        $expiredAt = $payload->expiredAt;
        if ($currentTime > $expiredAt) {
            throw JwtException::tokenExpired($payload);
        }
    }

    /**
     * @throws JwtException
     */
    private function checkIfRefreshable(TokenPayloadModel $payload): void
    {
        $currentTime = Carbon::now()->getTimestamp();
        $refreshUntil = $payload->refreshableUntil;
        if ($currentTime > $refreshUntil) {
            throw JwtException::tokenExpiredNotRefreshable();
        }
    }

    /**
     * @throws JwtException
     * @throws DataAccessException
     */
    public function refreshToken(string $token): string
    {
        try {
            $tokenPayload = $this->getTokenPayload($token);
        } catch (JwtException $exception) {
            if ($exception->getMessage() !== 'token expired') {
                throw $exception;
            }

            $expiredTokenPayload = $exception->getTokenPayload();
            return $this->refreshTokenByPayload($token, $expiredTokenPayload);
        }

        return $this->refreshTokenByPayload($token, $tokenPayload);
    }

    /**
     * @throws DataAccessException
     * @throws JwtException
     */
    private function refreshTokenByPayload(string $token, TokenPayloadModel $tokenPayload): string
    {
        try {
            $this->tokenStorageService->deleteToken($token);
        } catch (RedisException $e) {
            throw JwtException::invalidToken();
        }

        $userId = $tokenPayload->id;
        $user = $this->userRepository->getById($userId);
        return $this->createToken($user);
    }

    /**
     * @throws
     */
    public function logoutByToken(string $token): void
    {
        try {
            $this->tokenStorageService->deleteToken($token);
        } catch (RedisException $e) {
            throw JwtException::invalidToken();
        }
    }

    public function logoutAllExceptThis(string $token)
    {

    }
}
