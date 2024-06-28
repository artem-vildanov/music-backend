<?php

namespace App\Http\Controllers;

use App\DataAccessLayer\Repository\Interfaces\ISongRepository;
use App\DomainLayer\DomainMappers\SongDomainMapper;
use App\DtoLayer\DtoMappers\SongDtoMapper;
use App\Exceptions\DataAccessExceptions\DataAccessException;
use App\Exceptions\MinioException;
use App\Http\Requests\Song\CreateSongRequest;
use App\Http\Requests\Song\UpdateSongAudioRequest;
use App\Http\Requests\Song\UpdateSongNameRequest;
use App\Services\DomainServices\SongService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class SongController extends Controller
{


    public function __construct(
        private readonly ISongRepository  $songRepository,
        private readonly SongService      $songService,
        private readonly SongDtoMapper    $songDtoMapper,
        private readonly SongDomainMapper $songDomainMapper,
    ) {}

    /**
     * @throws DataAccessException
     */
    public function show(string $albumId, string $songId): JsonResponse
    {
        $songDb = $this->songRepository->getById($songId);
        $songDomain = $this->songDomainMapper->mapToDomain($songDb);
        $songDto = $this->songDtoMapper->mapToBigDto($songDomain);
        return response()->json($songDto);
    }

    /**
     * @throws DataAccessException
     * @throws MinioException
     */
    public function create(string $albumId, CreateSongRequest $request): JsonResponse
    {
        $data = $request->body();
        $songId = $this->songService->saveSong($data->name, $data->music, $albumId);
        return response()->json($songId);
    }

    /**
     * @throws DataAccessException
     */
    public function updateName(
        string $albumId,
        string $songId,
        UpdateSongNameRequest $request
    ): JsonResponse {
        $newName = $request->body();
        $this->songRepository->updateName($songId, $newName);
        return response()->json();
    }

    /**
     * @throws DataAccessException
     * @throws MinioException
     */
    public function updateAudio(
        string $albumId,
        string $songId,
        UpdateSongAudioRequest $request
    ): JsonResponse {
        $newPhoto = $request->body();
        $this->songService->updateSongAudio($songId, $newPhoto);
        return response()->json();
    }

    /**
     * @throws DataAccessException
     * @throws MinioException
     */
    public function delete(string $albumId, string $songId): JsonResponse
    {
        $this->songService->deleteSong($songId);
        return response()->json();
    }
}
