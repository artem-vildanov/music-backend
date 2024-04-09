<?php

namespace App\Http\Middleware;

use App\Exceptions\FavouritesExceptions\FavouriteAlbumsException;
use App\Facades\AuthFacade;
use App\Repository\Interfaces\IFavouritesRepository;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckAlbumIsFavourite
{
    public function __construct(
        private readonly IFavouritesRepository $favouritesRepository
    ) {}

    public function handle(Request $request, Closure $next): Response
    {
        $albumId = $request->route('albumId');
        $userId = AuthFacade::getUserId();

        if ($this->favouritesRepository->checkAlbumIsFavourite($userId, $albumId)) {
            throw FavouriteAlbumsException::failedAddToFavourites($albumId);
        }

        return $next($request);
    }
}
