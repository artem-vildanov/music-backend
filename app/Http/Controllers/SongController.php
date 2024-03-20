<?php

namespace App\Http\Controllers;

use App\Exceptions\DataAccessExceptions\DataAccessException;
use App\Exceptions\MinioException;
use App\Http\Requests\Song\CreateSongRequest;
use App\Http\Requests\Song\UpdateSongRequest;
use App\Mappers\SongMapper;
use App\Repository\Interfaces\ISongRepository;
use App\Services\DomainServices\SongService;
use Illuminate\Http\JsonResponse;

class SongController extends Controller
{


    public function __construct(
        private readonly ISongRepository   $songRepository,
        private readonly SongService       $songService,
        private readonly SongMapper $songMapper,
    ) {}

    /**
     * @throws DataAccessException
     */
    public function show(int $songId): JsonResponse
    {
        $song = $this->songRepository->getById($songId);
        $songDto = $this->songMapper->mapSingleSong($song);

        return response()->json($songDto);
    }

    /**
     * @throws DataAccessException
     * @throws MinioException
     */
    public function create(CreateSongRequest $request, int $albumId): JsonResponse
    {
        $data = $request->body();

        $songId = $this->songService->saveSong($data->name, $data->music, $albumId);

        return response()->json([
            'songId' => $songId,
            'message' => 'song created successfully'
        ]);
    }

    /**
     * @throws DataAccessException
     * @throws MinioException
     */
    public function update(int $songId, UpdateSongRequest $request): JsonResponse
    {
        $data = $request->body();

        $this->songService->updateSong($songId, $data->name, $data->music);
        return response()->json();
    }

    /**
     * @throws DataAccessException
     * @throws MinioException
     */
    public function delete(int $songId): JsonResponse
    {
        $this->songService->deleteSong($songId);
        return response()->json();
    }
}
