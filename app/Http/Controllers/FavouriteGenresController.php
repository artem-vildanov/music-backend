<?php

namespace App\Http\Controllers;

use App\DataAccessLayer\Repository\Interfaces\IFavouritesRepository;
use App\DataAccessLayer\Repository\Interfaces\IGenreRepository;
use App\DtoLayer\DtoMappers\GenreDtoMapper;
use App\Exceptions\FavouritesExceptions\FavouritesException;
use App\Facades\AuthFacade;
use App\Repository\Interfaces\IFavouriteGenresRepository;
use Illuminate\Http\JsonResponse;

class FavouriteGenresController extends Controller
{
    public function __construct(
        private readonly IFavouritesRepository $favouritesRepository,
        private readonly IGenreRepository      $genreRepository,
        private readonly GenreDtoMapper        $genreMapper,
    ) {}

    public function showFavouriteGenres(): JsonResponse
    {
        $userId = AuthFacade::getUserId();

        $genresIds = $this->favouritesRepository->getFavouriteGenresIds($userId);
        $genres = $this->genreRepository->getMultipleByIds($genresIds);
        $genreDtoCollection = $this->genreMapper->mapMultipleGenres($genres);

        return response()->json($genreDtoCollection);
    }

    /**
     * @throws FavouritesException
     */
    public function addToFavouriteGenres(int $genreId): JsonResponse
    {
        $userId = AuthFacade::getUserId();

        $this->favouritesRepository->addGenreToFavourites($genreId, $userId);
        $this->favouritesRepository->incrementGenreLikes($genreId);

        return response()->json();
    }

    /**
     * @throws FavouritesException
     */
    public function deleteFromFavouriteGenres(int $genreId): JsonResponse
    {
        $userId = AuthFacade::getUserId();

        $this->favouritesRepository->deleteGenreFromFavourites($genreId, $userId);
        $this->favouritesRepository->decrementGenreLikes($genreId);

        return response()->json();
    }
}
