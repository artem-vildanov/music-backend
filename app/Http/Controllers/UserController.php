<?php

namespace App\Http\Controllers;

use App\DataAccessLayer\Repository\Interfaces\IUserRepository;
use App\DomainLayer\DomainMappers\UserDomainMapper;
use App\DtoLayer\DtoMappers\UserDtoMapper;
use App\Facades\AuthFacade;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function __construct(
        private readonly IUserRepository $userRepository,
        private readonly UserDomainMapper $userDomainMapper,
        private readonly UserDtoMapper $userDtoMapper,
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

    }

    public function showFavouriteSongs(): JsonResponse
    {

    }

    public function showFavouriteArtists(): JsonResponse
    {

    }

    public function addAlbumToFavourites(string $albumId): JsonResponse
    {

    }

    public function removeAlbumFromFavourites(string $albumId): JsonResponse
    {

    }

    public function addSongToFavourites(string $songId): JsonResponse
    {

    }

    public function removeSongFromFavourites(string $songId): JsonResponse
    {

    }

    public function addArtistToFavourites(string $artistId): JsonResponse
    {

    }

    public function removeArtistFromFavourites(string $artistId): JsonResponse
    {

    }
}
