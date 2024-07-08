<?php

namespace App\Http\Controllers;

use App\DataAccessLayer\DbModels\Artist;
use App\DataAccessLayer\Repository\Interfaces\IAlbumRepository;
use App\DataAccessLayer\Repository\Interfaces\IArtistRepository;
use App\DomainLayer\DomainMappers\AlbumDomainMapper;
use App\DomainLayer\DomainMappers\ArtistDomainMapper;
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
        private readonly ArtistService      $artistService,
        private readonly AlbumService       $albumService,
        private readonly IArtistRepository  $artistRepository,
        private readonly IAlbumRepository   $albumRepository,
        private readonly TokenService       $tokenService,
        private readonly ArtistDtoMapper    $artistDtoMapper,
        private readonly AlbumDtoMapper     $albumDtoMapper,
        private readonly ArtistDomainMapper $artistDomainMapper,
        private readonly AlbumDomainMapper $albumDomainMapper,
    ) {}

    /**
     * @throws DataAccessException
     */
    public function show(string $artistId): JsonResponse
    {
        $artistDb = $this->artistRepository->getById($artistId);
        $artistDomain = $this->artistDomainMapper->mapToDomain($artistDb);
        $artistDto = $this->artistDtoMapper->mapToBigDto($artistDomain);

        return response()->json($artistDto);
    }

    // TODO для тестирования фронта, убрать потом
    public function showAll(): JsonResponse
    {
        $artistsDbGroup = $this->artistRepository->getAll();
        $artistsDomainGroup = $this->artistDomainMapper->mapMultipleToDomain($artistsDbGroup);
        $artistsDtoGroup = $this->artistDtoMapper->mapMultipleToLightDto($artistsDomainGroup);

        return response()->json($artistsDtoGroup);
    }

    public function showAlbumsMadeByArtist(string $artistId): JsonResponse
    {
        $albumsMadeByArtist = $this->albumRepository->getAllByArtist($artistId);
        $albumsMadeByArtist = $this->albumService->removePrivateAlbumsFromList($albumsMadeByArtist);
        $albumsDomainGroup = $this->albumDomainMapper->mapMultipleToDomain($albumsMadeByArtist);
        $albumsDtoGroup = $this->albumDtoMapper->mapMultipleToLightDto($albumsDomainGroup);

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
            'token' => $newToken
        ]);
    }

    /**
     * @throws DataAccessException
     * @throws MinioException
     */
    public function updateName(string $artistId, UpdateArtistNameRequest $request): JsonResponse
    {
        $newName = $request->body();
        $this->artistRepository->updateName($artistId, $newName);
        return response()->json();
    }

    public function updatePhoto(string $artistId, UpdateArtistPhotoRequest $request): JsonResponse
    {
        $newPhoto = $request->body();
        $this->artistService->updateArtistPhoto($artistId, $newPhoto);
        return response()->json();
    }

    /**
     * @throws DataAccessException
     * @throws MinioException
     * @throws JwtException
     */
    public function delete(string $artistId, Request $request): JsonResponse
    {
        $this->artistService->deleteArtist($artistId);

        $token = $this->tokenService->getTokenFromRequest($request);
        $newToken = $this->tokenService->refreshToken($token);

        return response()->json([
            'token' => $newToken
        ]);
    }
}
