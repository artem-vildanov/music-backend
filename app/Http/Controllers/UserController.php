<?php

namespace App\Http\Controllers;

use App\Exceptions\DataAccessExceptions\DataAccessException;
use App\Http\Requests\Auth\SignupRequest;
use App\Repository\Interfaces\IAlbumRepository;
use App\Repository\Interfaces\IArtistRepository;
use App\Repository\Interfaces\IFavouritesRepository;
use App\Repository\Interfaces\IGenreRepository;
use App\Repository\Interfaces\ISongRepository;
use App\Repository\Interfaces\IUserRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function __construct(
        private readonly IUserRepository $userRepository,
    ) {}

    /**
     * @throws DataAccessException
     */



}
