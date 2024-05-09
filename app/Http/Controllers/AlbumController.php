<?php

namespace App\Http\Controllers;

use App\Exceptions\DataAccessExceptions\DataAccessException;
use App\Exceptions\MinioException;
use App\Http\RequestModels\Album\UpdateAlbumPublishTimeModel;
use App\Http\Requests\Album\CreateAlbumRequest;
use App\Http\Requests\Album\UpdateAlbumPhotoRequest;
use App\Http\Requests\Album\UpdateAlbumPublishTimeRequest;
use App\Http\Requests\Album\UpdateAlbumNameAndGenreRequest;
use App\Mappers\AlbumMapper;
use App\Mappers\SongMapper;
use App\Repository\Interfaces\IAlbumRepository;
use App\Repository\Interfaces\IGenreRepository;
use App\Repository\Interfaces\ISongRepository;
use App\Services\CacheServices\AlbumCacheService;
use App\Services\DomainServices\AlbumService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class AlbumController extends Controller
{
    public function __construct(
        private readonly ISongRepository $songRepository,
        private readonly AlbumService $albumService,
        private readonly IAlbumRepository $albumRepository,
        private readonly AlbumMapper $albumMapper,
        private readonly SongMapper $songMapper,
        private readonly IGenreRepository $genreRepository
    ) {}

    /**
     * @throws DataAccessException
     */
    public function show(int $albumId): JsonResponse
    {
        $album = $this->albumRepository->getById($albumId);
        $albumDto = $this->albumMapper->mapSingleAlbum($album);

        return response()->json($albumDto);
    }

    /**
     * @throws DataAccessException
     */
    public function showSongsInAlbum(int $albumId): JsonResponse
    {
        $songsModelsGroup = $this->songRepository->getAllByAlbum($albumId);
        $songsDtoGroup = $this->songMapper->mapMultipleSongs($songsModelsGroup);

        return response()->json($songsDtoGroup);
    }

    /**
     * @throws DataAccessException
     * @throws MinioException
     */
    public function create(CreateAlbumRequest $request): JsonResponse
    {
        $data = $request->body();

        $albumId = $this->albumService->saveAlbum(
            $data->name,
            $data->photo,
            $data->genreId,
            $data->publishTime
        );

        return response()->json($albumId);
    }

    /**
     * @throws DataAccessException
     * @throws MinioException
     */
    public function updateNameAndGenre(int $albumId, UpdateAlbumNameAndGenreRequest $request): JsonResponse
    {
        $data = $request->body();

        $this->genreRepository->getById($data->genreId); // check for existance

        $this->albumRepository->updateNameAndGenre(
            $albumId,
            $data->name,
            $data->genreId,
        );

        return response()->json();
    }

    public function updatePublishTime(int $albumId, UpdateAlbumPublishTimeRequest $request): JsonResponse
    {
        $data = $request->body();
        $this->albumRepository->updatePublishTime($albumId, $data->publishTime);
        return response()->json();
    }

    public function updatePhoto(int $albumId, UpdateAlbumPhotoRequest $request): JsonResponse
    {
        $data = $request->body();
        $this->albumService->updateAlbumPhoto($albumId, $data->photo);
        return response()->json();
    }

    /**
     * @throws DataAccessException
     * @throws MinioException
     */
    public function delete(int $albumId): JsonResponse
    {
        $this->albumService->deleteAlbum($albumId);
        return response()->json();
    }
}
