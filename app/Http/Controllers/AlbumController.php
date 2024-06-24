<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\DataAccessLayer\Repository\Interfaces\IAlbumRepository;
use App\DataAccessLayer\Repository\Interfaces\ISongRepository;
use App\DomainLayer\DomainMappers\AlbumDomainMapper;
use App\DomainLayer\DomainMappers\SongDomainMapper;
use App\DtoLayer\DtoMappers\AlbumDtoMapper;
use App\DtoLayer\DtoMappers\SongDtoMapper;
use App\Exceptions\DataAccessExceptions\DataAccessException;
use App\Exceptions\GenreException;
use App\Exceptions\MinioException;
use App\Http\Requests\Album\CreateAlbumRequest;
use App\Http\Requests\Album\UpdateAlbumGenreRequest;
use App\Http\Requests\Album\UpdateAlbumNameRequest;
use App\Http\Requests\Album\UpdateAlbumPhotoRequest;
use App\Http\Requests\Album\UpdateAlbumPublishTimeRequest;
use App\Services\DomainServices\AlbumService;
use Illuminate\Http\JsonResponse;

class AlbumController extends Controller
{
    public function __construct(
        private readonly ISongRepository  $songRepository,
        private readonly AlbumService     $albumService,
        private readonly IAlbumRepository $albumRepository,
        private readonly AlbumDtoMapper   $albumDtoMapper,
        private readonly SongDtoMapper    $songDtoMapper,
        private readonly AlbumDomainMapper $albumDomainMapper,
        private readonly SongDomainMapper $songDomainMapper,
    ) {}

    /**
     * @throws DataAccessException
     */
    public function show(string $albumId): JsonResponse
    {
        $album = $this->albumRepository->getById($albumId);
        $albumDomain = $this->albumDomainMapper->mapToDomain($album);
        $albumDto = $this->albumDtoMapper->mapToBigDto($albumDomain);

        return response()->json($albumDto);
    }

    public function showSongsInAlbum(string $albumId): JsonResponse
    {
        $songsDbGroup = $this->songRepository->getAllByAlbum($albumId);
        $songsDomainGroup = $this->songDomainMapper->mapMultipleToDomain($songsDbGroup);
        $songsDtoGroup = $this->songDtoMapper->mapMultipleToLightDto($songsDomainGroup);

        return response()->json($songsDtoGroup);
    }

    /**
     * @throws DataAccessException
     * @throws GenreException
     */
    public function create(CreateAlbumRequest $request): JsonResponse
    {
        $data = $request->body();

        $albumId = $this->albumService->saveAlbum(
            $data->name,
            $data->photo,
            $data->genreName,
            $data->publishTime
        );

        return response()->json($albumId);
    }

    /**
     * @throws DataAccessException
     * @throws MinioException
     */
    public function updateName(string $albumId, UpdateAlbumNameRequest $request): JsonResponse
    {
        $albumName = $request->body();
        $this->albumRepository->updateName($albumId, $albumName);
        return response()->json();
    }

    /**
     * @throws GenreException
     */
    public function updateGenre(string $albumId, UpdateAlbumGenreRequest $request): JsonResponse
    {
        $albumGenre = $request->body();
        $this->albumService->checkGenreExists($albumGenre);
        $this->albumRepository->updateGenre($albumId, $albumGenre);
        return response()->json();
    }

    public function updatePublishTime(string $albumId, UpdateAlbumPublishTimeRequest $request): JsonResponse
    {
        $publishTime = $request->body();
        $this->albumRepository->updatePublishTime($albumId, $publishTime);
        return response()->json();
    }

    public function updatePhoto(string $albumId, UpdateAlbumPhotoRequest $request): JsonResponse
    {
        $photo = $request->body();
        $this->albumService->updateAlbumPhoto($albumId, $photo);
        return response()->json();
    }

    /**
     * @throws DataAccessException
     * @throws MinioException
     */
    public function delete(string $albumId): JsonResponse
    {
        $this->albumService->deleteAlbum($albumId);
        return response()->json();
    }
}
