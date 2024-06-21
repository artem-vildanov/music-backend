<?php

namespace App\Http\Controllers;

use App\DataAccessLayer\Repository\Interfaces\IPlaylistRepository;
use App\DataAccessLayer\Repository\Interfaces\IPlaylistSongsRepository;
use App\DataAccessLayer\Repository\Interfaces\ISongRepository;
use App\DtoLayer\DtoMappers\PlaylistDtoMapper;
use App\DtoLayer\DtoMappers\SongDtoMapper;
use App\Exceptions\DataAccessExceptions\DataAccessException;
use App\Exceptions\MinioException;
use App\Exceptions\PlaylistSongsException;
use App\Facades\AuthFacade;
use App\Http\Requests\Playlist\CreatePlaylistRequest;
use App\Http\Requests\Playlist\UpdatePlaylistNameRequest;
use App\Http\Requests\Playlist\UpdatePlaylistPhotoRequest;
use App\Services\DomainServices\PlaylistService;
use Illuminate\Http\JsonResponse;

class PlaylistController extends Controller
{
    public function __construct(
        private readonly IPlaylistRepository      $playlistRepository,
        private readonly IPlaylistSongsRepository $playlistSongsRepository,
        private readonly ISongRepository          $songRepository,
        private readonly PlaylistDtoMapper        $playlistMapper,
        private readonly SongDtoMapper            $songMapper,
        private readonly PlaylistService          $playlistService,
    ) {}

    /**
     * @throws DataAccessException
     */
    public function show(int $playlistId): JsonResponse
    {
        $playlist = $this->playlistRepository->getById($playlistId);
        $playlistDto = $this->playlistMapper->mapSinglePlaylist($playlist);

        return response()->json($playlistDto);
    }

    public function showSongsInPlaylist(int $playlistId): JsonResponse
    {
        $songsIdsGroup = $this->playlistSongsRepository->getSongsIdsContainedInPlaylist($playlistId);
        $songsModelsGroup = $this->songRepository->getMultipleByIds($songsIdsGroup);
        $songsDtoGroup = $this->songMapper->mapMultipleSongs($songsModelsGroup);

        return response()->json($songsDtoGroup);
    }

    public function showUserPlaylists(): JsonResponse
    {
        $userId = AuthFacade::getUserId();

        $playlists = $this->playlistRepository->getPlaylistsModelsByUserId($userId);
        $playlistDtoCollection = $this->playlistMapper->mapMultiplePlaylists($playlists);

        return response()->json($playlistDtoCollection);
    }

    /**
     * @throws PlaylistSongsException
     */
    public function addSongToPlaylist(int $playlistId, int $songId): JsonResponse
    {
        $this->playlistSongsRepository->addSongToPlaylist($songId, $playlistId);
        return response()->json();
    }

    /**
     * @throws PlaylistSongsException
     */
    public function deleteSongsFromPlaylist(int $playlistId, int $songId): JsonResponse
    {
        $this->playlistSongsRepository->deleteSongFromPlaylist($songId, $playlistId);
        return response()->json();
    }

    /**
     * @throws DataAccessException
     * @throws MinioException
     */
    public function create(CreatePlaylistRequest $request): JsonResponse
    {
        $data = $request->body();
        $authUserId = AuthFacade::getUserId();
        $playlistId = $this->playlistRepository->create($data->name, $authUserId);
        return response()->json([
            'playlistId' => $playlistId
        ]);
    }

    /**
     * @throws DataAccessException
     * @throws MinioException
     */
    public function updateName(int $playlistId, UpdatePlaylistNameRequest $request): JsonResponse
    {
        $data = $request->body();
        $this->playlistRepository->updateName($playlistId, $data->name);
        return response()->json();
    }

    public function updatePhoto(int $playlistId, UpdatePlaylistPhotoRequest $request): JsonResponse
    {
        $data = $request->body();
        $this->playlistService->updatePlaylistPhoto($playlistId, $data->photo);
        return response()->json();

        //UpdatePlaylistPhotoRequest
    }


    /**
     * @throws DataAccessException
     * @throws MinioException
     */
    public function delete(int $playlistId): JsonResponse
    {
        $this->playlistService->deletePlaylist($playlistId);

        return response()->json();
    }
}
