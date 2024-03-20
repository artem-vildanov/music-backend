<?php

namespace App\Http\Middleware;

use App\Exceptions\DataAccessExceptions\DataAccessException;
use App\Facades\AuthFacade;
use App\Repository\Interfaces\IArtistRepository;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ArtistOwnership
{
    public function __construct(

    ) {}

    /**
     * @throws DataAccessException
     */
    public function handle(Request $request, Closure $next): Response
    {
        $requestArtistId = (int)$request->route('artistId');

        $authUser = AuthFacade::getAuthInfo();

        if ($requestArtistId !== $authUser->artistId) {
            return response()->json([
                'error' => 'You are not permitted to access this resource.',
            ], 403);
        }


        return $next($request);
    }
}
