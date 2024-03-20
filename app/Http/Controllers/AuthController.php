<?php

namespace App\Http\Controllers;

use App\Exceptions\DataAccessExceptions\DataAccessException;
use App\Exceptions\JwtException;
use App\Facades\AuthFacade;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\SignupRequest;
use App\Repository\UserRepository;
use App\Services\JwtServices\TokenService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function __construct(
        private readonly UserRepository      $userRepository,
        private readonly TokenService        $tokenService
    ) {
    }


    /**
     * @throws DataAccessException
     */
    public function signup(SignupRequest $request): JsonResponse
    {
        $data = $request->body();

        $user = $this->userRepository->create(
            $data->name,
            Hash::make($data->password),
            $data->email,
            'base_user',
        );

        $token = $this->tokenService->createToken($user);
        return $this->respondWithToken($token);
    }

    /**
     * @throws DataAccessException
     */
    public function login(LoginRequest $request): JsonResponse
    {
        $credentials = $request->body();

        $user = $this->userRepository->getByEmail($credentials->email);
        if (!Hash::check($credentials->password, $user->password)) {
            return response()->json([],403);
        }

        $token = $this->tokenService->createToken($user);

        return $this->respondWithToken($token);
    }

    public function me(): JsonResponse
    {
        $tokenPayload = AuthFacade::getAuthInfo();
        return response()->json($tokenPayload);
    }

    /**
     * @throws JwtException
     */
    public function logout(Request $request): JsonResponse
    {
        $token = $this->tokenService->getTokenFromRequest($request);
        $this->tokenService->logoutByToken($token);

        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * @throws JwtException
     * @throws DataAccessException
     */
    public function refresh(Request $request): JsonResponse
    {
        $token = $this->tokenService->getTokenFromRequest($request);
        $newToken = $this->tokenService->refreshToken($token);

        return $this->respondWithToken($newToken);
    }

    protected function respondWithToken($token): JsonResponse
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'expires_in' => (int)config('jwt.ttl'),
        ]);
    }
}
