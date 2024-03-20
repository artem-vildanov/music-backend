<?php

namespace App\Http\Controllers;

use App\Exceptions\FavouritesExceptions\FavouritesException;
use App\Facades\AuthFacade;
use App\Mappers\ArtistMapper;
use App\Repository\Interfaces\IAlbumRepository;
use App\Repository\Interfaces\IArtistRepository;
use App\Repository\Interfaces\IFavouriteAlbumsRepository;
use App\Repository\Interfaces\IFavouriteArtistsRepository;
use App\Repository\Interfaces\IFavouritesRepository;
use App\Repository\Interfaces\IGenreRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FavouriteArtistsController extends Controller
{
    public function __construct(
        private readonly IFavouritesRepository $favouritesRepository,
        private readonly ArtistMapper $artistMapper,
        private readonly IArtistRepository $artistRepository,
    ) {}

    public function showFavouriteArtists(): JsonResponse
    {
        $userId = AuthFacade::getUserId();

        $artistsIds = $this->favouritesRepository->getFavouriteArtistsIds($userId);
        $artists = $this->artistRepository->getMultipleByIds($artistsIds);
        $artistDtoCollection = $this->artistMapper->mapMultipleArtists($artists);

        return response()->json($artistDtoCollection);
    }

    /**
     * @throws FavouritesException
     */
    public function addToFavouriteArtists(int $artistId): JsonResponse
    {
        $userId = AuthFacade::getUserId();

        $this->favouritesRepository->addArtistToFavourites($artistId, $userId);
        $this->favouritesRepository->incrementArtistLikes($artistId);

        return response()->json();
    }

    /**
     * @throws FavouritesException
     */
    public function deleteFromFavouriteArtists(int $artistId): JsonResponse
    {
        $userId = AuthFacade::getUserId();

        $this->favouritesRepository->deleteArtistFromFavourites($artistId, $userId);
        $this->favouritesRepository->decrementArtistLikes($artistId);

        return response()->json();
    }
}
