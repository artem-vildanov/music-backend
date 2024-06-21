<?php

namespace App\Http\Controllers;

use App\DataAccessLayer\Repository\Interfaces\IArtistRepository;
use App\DataAccessLayer\Repository\Interfaces\IFavouritesRepository;
use App\DtoLayer\DtoMappers\ArtistDtoMapper;
use App\Exceptions\FavouritesExceptions\FavouritesException;
use App\Facades\AuthFacade;
use App\Repository\Interfaces\IFavouriteAlbumsRepository;
use App\Repository\Interfaces\IFavouriteArtistsRepository;
use Illuminate\Http\JsonResponse;

class FavouriteArtistsController extends Controller
{
    public function __construct(
        private readonly IFavouritesRepository $favouritesRepository,
        private readonly ArtistDtoMapper       $artistMapper,
        private readonly IArtistRepository     $artistRepository,
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
