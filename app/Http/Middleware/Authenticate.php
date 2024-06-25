<?php

declare(strict_types = 1);

namespace App\Http\Middleware;

use App\DomainLayer\DomainModels\TokenPayloadModel;
use App\Exceptions\JwtException;
use App\Services\JwtServices\TokenService;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class Authenticate
{
    public function __construct(
        private readonly TokenService $tokenService,
    ) {}

    /**
     * @throws JwtException
     */
    function handle(Request $request, Closure $next)
    {
        $authInfo = $this->getAuthInfo($request);
        $request->attributes->add(['authInfo' => $authInfo]);

        return $next($request);
    }

    /**
     * @throws JwtException
     */
    private function getAuthInfo(Request $request): TokenPayloadModel
    {
        $token = $this->tokenService->getTokenFromRequest($request);
        return $this->tokenService->getTokenPayload($token);
    }
}
