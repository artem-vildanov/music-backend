<?php

namespace App\Http\Middleware;

use App\Facades\AuthFacade;
use App\Repository\Interfaces\IFavouritesRepository;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckArtistIsFavourite
{
    public function __construct(
        private readonly IFavouritesRepository $favouritesRepository
    ) {}

    public function handle(Request $request, Closure $next): Response
    {
        $artistId = $request->route('artistId');
        $userId = AuthFacade::getUserId();

        if (!$this->favouritesRepository->checkArtistIsFavourite($userId, $artistId)) {
            return response()->json([
                'message' => 'failed to add artist to favourites'
            ]);
        }

        return $next($request);
    }
}
