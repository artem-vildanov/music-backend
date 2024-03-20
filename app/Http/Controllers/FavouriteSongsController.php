<?php

namespace App\Http\Controllers;

use App\Exceptions\FavouritesExceptions\FavouritesException;
use App\Facades\AuthFacade;
use App\Mappers\SongMapper;
use App\Repository\Interfaces\IFavouriteSongsRepository;
use App\Repository\Interfaces\IFavouritesRepository;
use App\Repository\Interfaces\ISongRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FavouriteSongsController extends Controller
{
    public function __construct(
        private readonly IFavouritesRepository $favouritesRepository,
        private readonly ISongRepository $songRepository,
        private readonly SongMapper $songMapper,
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
    public function addToFavouriteSongs(int $albumId, int $songId): JsonResponse
    {
        $userId = AuthFacade::getUserId();

        $this->favouritesRepository->addSongToFavourites($songId, $userId);
        $this->favouritesRepository->incrementSongLikes($songId);

        return response()->json();
    }

    /**
     * @throws FavouritesException
     */
    public function deleteFromFavouriteSongs(int $albumId, int $songId): JsonResponse
    {
        $userId = AuthFacade::getUserId();

        $this->favouritesRepository->deleteSongFromFavourites($songId, $userId);
        $this->favouritesRepository->decrementSongLikes($songId);

        return response()->json();
    }
}
