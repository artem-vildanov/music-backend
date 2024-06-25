<?php

namespace App\Http\Controllers;

use App\DataAccessLayer\Queries\FavouriteQuery;
use App\DataAccessLayer\Repository\Interfaces\IAlbumRepository;
use App\DataAccessLayer\Repository\Interfaces\IArtistRepository;
use App\DataAccessLayer\Repository\Interfaces\ISongRepository;
use App\DataAccessLayer\Repository\Interfaces\IUserRepository;
use App\DomainLayer\DomainMappers\AlbumDomainMapper;
use App\DomainLayer\DomainMappers\ArtistDomainMapper;
use App\DomainLayer\DomainMappers\SongDomainMapper;
use App\DomainLayer\DomainMappers\UserDomainMapper;
use App\DtoLayer\DtoMappers\AlbumDtoMapper;
use App\DtoLayer\DtoMappers\ArtistDtoMapper;
use App\DtoLayer\DtoMappers\SongDtoMapper;
use App\DtoLayer\DtoMappers\UserDtoMapper;
use App\Facades\AuthFacade;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function __construct(
        private readonly SongDtoMapper $songDtoMapper,
        private readonly SongDomainMapper $songDomainMapper,
        private readonly ArtistDtoMapper $artistDtoMapper,
        private readonly ArtistDomainMapper $artistDomainMapper,
        private readonly AlbumDtoMapper $albumDtoMapper,
        private readonly AlbumDomainMapper $albumDomainMapper,
        private readonly UserDomainMapper $userDomainMapper,
        private readonly UserDtoMapper $userDtoMapper,
        private readonly FavouriteQuery $favouriteQuery,
        private readonly IUserRepository $userRepository,
        private readonly IAlbumRepository $albumRepository,
        private readonly IArtistRepository $artistRepository,
        private readonly ISongRepository$songRepository,
    ) {}

    public function showUserInfo(): JsonResponse
    {
        $authUserId = AuthFacade::getUserId();
        $userDb = $this->userRepository->getById($authUserId);
        $userDomain = $this->userDomainMapper->mapToDomain($userDb);
        $userDto = $this->userDtoMapper->mapToLightDto($userDomain);
        return response()->json($userDto);
    }

    public function showFavouriteAlbums(): JsonResponse
    {
        $authUserId = AuthFacade::getUserId();
        $favouritesDbGroup = $this->favouriteQuery->getFavouriteAlbums($authUserId);
        $favouritesDomainGroup = $this->albumDomainMapper->mapMultipleToDomain($favouritesDbGroup);
        $favouritesDtoGroup = $this->albumDtoMapper->mapMultipleToLightDto($favouritesDomainGroup);
        return response()->json($favouritesDtoGroup);
    }

    public function showFavouriteSongs(): JsonResponse
    {
        $authUserId = AuthFacade::getUserId();
        $favouritesDbGroup = $this->favouriteQuery->getFavouriteSongs($authUserId);
        $favouritesDomainGroup = $this->songDomainMapper->mapMultipleToDomain($favouritesDbGroup);
        $favouritesDtoGroup = $this->songDtoMapper->mapMultipleToLightDto($favouritesDomainGroup);
        return response()->json($favouritesDtoGroup);
    }

    public function showFavouriteArtists(): JsonResponse
    {
        $authUserId = AuthFacade::getUserId();
        $favouritesDbGroup = $this->favouriteQuery->getFavouriteArtists($authUserId);
        $favouritesDomainGroup = $this->artistDomainMapper->mapMultipleToDomain($favouritesDbGroup);
        $favouritesDtoGroup = $this->artistDtoMapper->mapMultipleToLightDto($favouritesDomainGroup);
        return response()->json($favouritesDtoGroup);
    }

    public function addAlbumToFavourites(string $albumId): JsonResponse
    {
        $authUserId = AuthFacade::getUserId();
        $this->userRepository->addAlbumToFavourites($authUserId, $albumId);
        $this->albumRepository->incrementLikes($albumId);
        return response()->json();
    }

    public function removeAlbumFromFavourites(string $albumId): JsonResponse
    {
        $authUserId = AuthFacade::getUserId();
        $this->userRepository->removeAlbumFromFavourites($authUserId, $albumId);
        $this->albumRepository->decrementLikes($albumId);
        return response()->json();
    }

    public function addSongToFavourites(string $songId): JsonResponse
    {
        $authUserId = AuthFacade::getUserId();
        $this->userRepository->addSongToFavourites($authUserId, $songId);
        $this->songRepository->incrementLikes($songId);
        return response()->json();
    }

    public function removeSongFromFavourites(string $songId): JsonResponse
    {
        $authUserId = AuthFacade::getUserId();
        $this->userRepository->removeSongFromFavourites($authUserId, $songId);
        $this->songRepository->decrementLikes($songId);
        return response()->json();
    }

    public function addArtistToFavourites(string $artistId): JsonResponse
    {
        $authUserId = AuthFacade::getUserId();
        $this->userRepository->addArtistToFavourites($authUserId, $artistId);
        $this->artistRepository->incrementLikes($artistId);
        return response()->json();
    }

    public function removeArtistFromFavourites(string $artistId): JsonResponse
    {
        $authUserId = AuthFacade::getUserId();
        $this->userRepository->removeArtistFromFavourites($authUserId, $artistId);
        $this->artistRepository->decrementLikes($artistId);
        return response()->json();
    }
}
