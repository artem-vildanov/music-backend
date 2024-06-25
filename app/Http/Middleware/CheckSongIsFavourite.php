<?php

namespace App\Http\Middleware;

use App\DataAccessLayer\Repository\Interfaces\IFavouritesRepository;
use App\DataAccessLayer\Repository\Interfaces\IUserRepository;
use App\Exceptions\FavouritesExceptions\FavouriteSongsException;
use App\Facades\AuthFacade;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckSongIsFavourite
{
    public function __construct(
        private readonly IUserRepository $userRepository,
    ) {}

    public function handle(Request $request, Closure $next): Response
    {
        $songId = $request->route('songId');
        $userId = AuthFacade::getUserId();

        if ($this->userRepository->checkSongFavourite($userId, $songId)) {
            throw FavouriteSongsException::failedAddToFavourites($songId);
        }

        return $next($request);
    }
}
