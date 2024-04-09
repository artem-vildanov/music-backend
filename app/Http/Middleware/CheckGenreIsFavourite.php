<?php

namespace App\Http\Middleware;

use App\Exceptions\DataAccessExceptions\GenreException;
use App\Exceptions\FavouritesExceptions\FavouriteGenresException;
use App\Exceptions\FavouritesExceptions\FavouritesException;
use App\Facades\AuthFacade;
use App\Repository\Interfaces\IFavouritesRepository;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckGenreIsFavourite
{
    public function __construct(
        private readonly IFavouritesRepository $favouritesRepository
    ) {}

    public function handle(Request $request, Closure $next): Response
    {
        $genreId = $request->route('genreId');
        $userId = AuthFacade::getUserId();

        if ($this->favouritesRepository->checkGenreIsFavourite($userId, $genreId)) {
            throw FavouriteGenresException::failedAddToFavourites($genreId);
        }

        return $next($request);
    }
}
