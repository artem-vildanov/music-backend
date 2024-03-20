<?php

namespace App\Http\Middleware;

use App\Facades\AuthFacade;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ForBaseUserPermitted
{
    public function handle(Request $request, Closure $next): Response
    {
        $authUser = AuthFacade::getAuthInfo();

        if ($authUser->artistId)
        {
            return response()->json([
                'error' => 'You are already have artist account.',
            ], 403);
        }

        return $next($request);
    }
}
