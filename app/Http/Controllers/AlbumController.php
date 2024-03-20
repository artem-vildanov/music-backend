<?php

namespace App\Http\Controllers;

use App\Exceptions\DataAccessExceptions\DataAccessException;
use App\Exceptions\MinioException;
use App\Http\Requests\Album\CreateAlbumRequest;
use App\Http\Requests\Album\UpdateAlbumRequest;
use App\Mappers\AlbumMapper;
use App\Mappers\SongMapper;
use App\Repository\Interfaces\IAlbumRepository;
use App\Repository\Interfaces\ISongRepository;
use App\Services\CacheServices\AlbumCacheService;
use App\Services\DomainServices\AlbumService;
use Illuminate\Http\JsonResponse;

class AlbumController extends Controller
{
    public function __construct(
        private readonly ISongRepository $songRepository,
        private readonly AlbumService $albumService,
        private readonly IAlbumRepository $albumRepository,
        private readonly AlbumMapper $albumMapper,
        private readonly SongMapper $songMapper,
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
            $data->genreId
        );

        return response()->json([
            "album with id = {$albumId} created successfully"
        ]);
    }

    /**
     * @throws DataAccessException
     * @throws MinioException
     */
    public function update(int $albumId, UpdateAlbumRequest $request): JsonResponse
    {
        $data = $request->body();

        $this->albumService->updateAlbum(
            $albumId,
            $data->name,
            $data->photo,
            $data->status,
            $data->genreId
        );

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
