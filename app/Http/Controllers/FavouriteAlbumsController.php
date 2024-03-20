<?php

namespace App\Http\Controllers;

use App\DataTransferObjects\AlbumDto;
use App\Exceptions\FavouritesExceptions\FavouritesException;
use App\Facades\AuthFacade;
use App\Mappers\AlbumMapper;
use App\Models\Album;
use App\Repository\Interfaces\IAlbumRepository;
use App\Repository\Interfaces\IArtistRepository;
use App\Repository\Interfaces\IFavouriteAlbumsRepository;
use App\Repository\Interfaces\IFavouritesRepository;
use App\Repository\Interfaces\IGenreRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FavouriteAlbumsController extends Controller
{
    public function __construct(
        private readonly IFavouritesRepository $favouritesRepository,
        private readonly IAlbumRepository $albumRepository,
        private readonly AlbumMapper $albumMapper,
    ) {}

    public function showFavouriteAlbums(): JsonResponse
    {
        $userId = AuthFacade::getUserId();

        $albumsIds = $this->favouritesRepository->getFavouriteAlbumsIds($userId);
        $albums = $this->albumRepository->getMultipleByIds($albumsIds);
        $albumDtoCollection = $this->albumMapper->mapMultipleAlbums($albums);

        return response()->json($albumDtoCollection);
    }

    /**
     * @throws FavouritesException
     */
    public function addToFavouriteAlbums(int $albumId): JsonResponse
    {
        $userId = AuthFacade::getUserId();

        $this->favouritesRepository->addAlbumToFavourites($albumId, $userId);
        $this->favouritesRepository->incrementAlbumLikes($albumId);

        return response()->json();
    }

    /**
     * @throws FavouritesException
     */
    public function deleteFromFavouriteAlbums(int $albumId): JsonResponse
    {
        $userId = AuthFacade::getUserId();

        $this->favouritesRepository->deleteAlbumFromFavourites($albumId, $userId);
        $this->favouritesRepository->decrementAlbumLikes($albumId);

        return response()->json();
    }
}
