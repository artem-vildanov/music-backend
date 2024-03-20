<?php

namespace App\Http\Middleware;

use App\Facades\AuthFacade;
use App\Repository\Interfaces\IFavouritesRepository;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckSongIsFavourite
{
    public function __construct(
        private readonly IFavouritesRepository $favouritesRepository
    ) {}

    public function handle(Request $request, Closure $next): Response
    {
        $songId = $request->route('songId');
        $userId = AuthFacade::getUserId();

        if (!$this->favouritesRepository->checkSongIsFavourite($userId, $songId)) {
            return response()->json([
                'message' => 'failed to add song to favourites'
            ]);
        }

        return $next($request);
    }
}
