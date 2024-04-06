<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Services\JwtServices\TokenService;
use App\Models\TokenPayloadModel;

class Authenticate
{
    public function __construct(
        private readonly TokenService $tokenService,
    ) {}
    
    function handle(Request $request, Closure $next)
    {
        $authInfo = $this->getAuthInfo($request);
        $request->attributes->add(['authInfo' => $authInfo]);

        return $next($request);
    }

    private function getAuthInfo(Request $request): ?TokenPayloadModel
    {
        $token = $this->tokenService->getTokenFromRequest($request);
        return $this->tokenService->getTokenPayload($token);
    }
}
