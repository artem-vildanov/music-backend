<?php

namespace App\Http\Controllers;

use App\DataAccessLayer\DbModels\Artist;
use App\DataAccessLayer\Repository\Interfaces\IAlbumRepository;
use App\DataAccessLayer\Repository\Interfaces\IArtistRepository;
use App\DtoLayer\DtoMappers\AlbumDtoMapper;
use App\DtoLayer\DtoMappers\ArtistDtoMapper;
use App\Exceptions\DataAccessExceptions\DataAccessException;
use App\Exceptions\JwtException;
use App\Exceptions\MinioException;
use App\Http\Requests\Artist\CreateArtistRequest;
use App\Http\Requests\Artist\UpdateArtistNameRequest;
use App\Http\Requests\Artist\UpdateArtistPhotoRequest;
use App\Services\DomainServices\AlbumService;
use App\Services\DomainServices\ArtistService;
use App\Services\JwtServices\TokenService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ArtistController extends Controller
{
    public function __construct(
        private readonly ArtistService     $artistService,
        private readonly AlbumService      $albumService,
        private readonly IArtistRepository $artistRepository,
        private readonly IAlbumRepository  $albumRepository,
        private readonly TokenService      $tokenService,
        private readonly ArtistDtoMapper   $artistMapper,
        private readonly AlbumDtoMapper $albumMapper
    ) {}

    /**
     * @throws DataAccessException
     */
    public function show(int $artistId): JsonResponse
    {
        $artist = $this->artistRepository->getById($artistId);
        $artistDto = $this->artistMapper->mapSingleArtist($artist);

        return response()->json($artistDto);
    }

    // TODO для тестирования фронта, убрать потом
    public function showAll(): JsonResponse
    {
        $artists = Artist::all()->all();
        $artistsDtoGroup = $this->artistMapper->mapMultipleArtists($artists);

        return response()->json($artistsDtoGroup);
    }

    /**
     * @throws DataAccessException
     */
    public function showAlbumsMadeByArtist(int $artistId): JsonResponse
    {
        $albumsMadeByArtist = $this->albumRepository->getAllByArtist($artistId);
        $albumsMadeByArtist = $this->albumService->removePrivateAlbumsFromList($albumsMadeByArtist);
        $albumsDtoGroup = $this->albumMapper->mapMultipleAlbums($albumsMadeByArtist);

        return response()->json($albumsDtoGroup);
    }

    /**
     * @throws DataAccessException
     * @throws MinioException
     * @throws JwtException
     */
    public function create(CreateArtistRequest $request): JsonResponse
    {
        $data = $request->body();

        $artistId = $this->artistService->saveArtist($data->name, $data->photo);

        $token = $this->tokenService->getTokenFromRequest($request);
        $newToken = $this->tokenService->refreshToken($token);

        return response()->json([
            'artistId' => $artistId,
            'access_token' => $newToken
        ]);
    }

    /**
     * @throws DataAccessException
     * @throws MinioException
     */
    public function updateName(int $artistId, UpdateArtistNameRequest $request): JsonResponse
    {
        $data = $request->body();
        $this->artistRepository->updateName($artistId, $data->name);
        return response()->json();
    }

    public function updatePhoto(int $artistId, UpdateArtistPhotoRequest $request): JsonResponse
    {
        $data = $request->body();
        $this->artistService->updateArtistPhoto($artistId, $data->photo);
        return response()->json();
    }

    /**
     * @throws DataAccessException
     * @throws MinioException
     * @throws JwtException
     */
    public function delete(int $artistId, Request $request): JsonResponse
    {
        $this->artistService->deleteArtist($artistId);

        $token = $this->tokenService->getTokenFromRequest($request);
        $newToken = $this->tokenService->refreshToken($token);

        return response()->json([
            'message' => 'artist deleted successfully, your token has been refreshed',
            'access_token' => $newToken
        ]);
    }
}
