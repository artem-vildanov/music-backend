<?php

namespace App\Http\Controllers;

use App\DataAccessLayer\Repository\Interfaces\IFavouritesRepository;
use App\DataAccessLayer\Repository\Interfaces\ISongRepository;
use App\DtoLayer\DtoMappers\SongDtoMapper;
use App\Exceptions\FavouritesExceptions\FavouritesException;
use App\Facades\AuthFacade;
use App\Repository\Interfaces\IFavouriteSongsRepository;
use Illuminate\Http\JsonResponse;

class FavouriteSongsController extends Controller
{
    public function __construct(
        private readonly IFavouritesRepository $favouritesRepository,
        private readonly ISongRepository       $songRepository,
        private readonly SongDtoMapper         $songMapper,
    ) {}

    public function showFavouriteSongs(): JsonResponse
    {
        $userId = AuthFacade::getUserId();

        $genresIds = $this->favouritesRepository->getFavouriteSongsIds($userId);
        $genres = $this->songRepository->getMultipleByIds($genresIds);
        $genreDtoCollection = $this->songMapper->mapMultipleSongs($genres);

        return response()->json($genreDtoCollection);
    }

    /**
     * @throws FavouritesException
     */
    public function addToFavouriteSongs(int $songId): JsonResponse
    {
        $userId = AuthFacade::getUserId();

        $this->favouritesRepository->addSongToFavourites($songId, $userId);
        $this->favouritesRepository->incrementSongLikes($songId);

        return response()->json();
    }

    /**
     * @throws FavouritesException
     */
    public function deleteFromFavouriteSongs(int $songId): JsonResponse
    {
        $userId = AuthFacade::getUserId();

        $this->favouritesRepository->deleteSongFromFavourites($songId, $userId);
        $this->favouritesRepository->decrementSongLikes($songId);

        return response()->json();
    }
}
