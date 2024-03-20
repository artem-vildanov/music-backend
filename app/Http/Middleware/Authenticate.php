<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class Authenticate
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    function handle(Request $request, Closure $next)
    {
        $authInfo = $request->get('authInfo');

        if ($authInfo === null) {
            return new Response(status: ResponseAlias::HTTP_UNAUTHORIZED);
        }

        return $next($request);
    }
}
