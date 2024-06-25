<?php

namespace App\Http\Middleware;

use App\DataAccessLayer\Repository\Interfaces\IUserRepository;
use App\Exceptions\FavouritesExceptions\FavouriteArtistsException;
use App\Facades\AuthFacade;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckArtistIsFavourite
{
    public function __construct(
        private readonly IUserRepository $userRepository,
    ) {}

    public function handle(Request $request, Closure $next): Response
    {
        $artistId = $request->route('artistId');
        $userId = AuthFacade::getUserId();

        if ($this->userRepository->checkArtistFavourite($userId, $artistId)) {
            throw FavouriteArtistsException::failedAddToFavourites($artistId);
        }

        return $next($request);
    }
}
