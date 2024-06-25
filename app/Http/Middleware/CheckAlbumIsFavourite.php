<?php

namespace App\Http\Middleware;

use App\DataAccessLayer\Repository\Interfaces\IFavouritesRepository;
use App\DataAccessLayer\Repository\Interfaces\IUserRepository;
use App\Exceptions\FavouritesExceptions\FavouriteAlbumsException;
use App\Facades\AuthFacade;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckAlbumIsFavourite
{
    public function __construct(
        private readonly IUserRepository $userRepository,
    ) {}

    public function handle(Request $request, Closure $next): Response
    {
        $albumId = $request->route('albumId');
        $userId = AuthFacade::getUserId();

        if ($this->userRepository->checkAlbumFavourite($userId, $albumId)) {
            throw FavouriteAlbumsException::failedAddToFavourites($albumId);
        }

        return $next($request);
    }
}
