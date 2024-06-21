<?php

namespace App\Http\Controllers;

use App\DataAccessLayer\Repository\Interfaces\ISongRepository;
use App\DtoLayer\DtoMappers\SongDtoMapper;
use App\Exceptions\DataAccessExceptions\DataAccessException;
use App\Exceptions\MinioException;
use App\Http\Requests\Song\CreateSongRequest;
use App\Http\Requests\Song\UpdateSongAudioRequest;
use App\Http\Requests\Song\UpdateSongNameRequest;
use App\Services\DomainServices\SongService;
use Illuminate\Http\JsonResponse;

class SongController extends Controller
{


    public function __construct(
        private readonly ISongRepository $songRepository,
        private readonly SongService     $songService,
        private readonly SongDtoMapper   $songMapper,
    ) {}

    /**
     * @throws DataAccessException
     */
    public function show(int $albumId, int $songId): JsonResponse
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

        return response()->json($songId);
    }

    /**
     * @throws DataAccessException
     * @throws MinioException
     */
    public function updateName(int $albumId, int $songId, UpdateSongNameRequest $request): JsonResponse
    {
        $data = $request->body();
        $this->songRepository->updateName($songId, $data->name);
        return response()->json();
    }

    public function updateAudio(int $albumId, int $songId, UpdateSongAudioRequest $request): JsonResponse
    {
        $data = $request->body();
        $this->songService->updateSongAudio($songId, $data->audio);
        return response()->json();
    }

    /**
     * @throws DataAccessException
     * @throws MinioException
     */
    public function delete(int $albumId, int $songId): JsonResponse
    {
        $this->songService->deleteSong($songId);
        return response()->json();
    }
}
