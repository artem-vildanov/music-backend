<?php

namespace App\Http\Controllers;

use App\Exceptions\DataAccessExceptions\DataAccessException;
use App\Exceptions\MinioException;
use App\Exceptions\PlaylistSongsException;
use App\Facades\AuthFacade;
use App\Http\Requests\Playlist\CreatePlaylistRequest;
use App\Http\Requests\Playlist\UpdatePlaylistRequest;
use App\Mappers\PlaylistMapper;
use App\Mappers\SongMapper;
use App\Repository\Interfaces\IPlaylistRepository;
use App\Repository\Interfaces\IPlaylistSongsRepository;
use App\Repository\Interfaces\ISongRepository;
use App\Services\DomainServices\PlaylistService;
use Illuminate\Http\JsonResponse;

class PlaylistController extends Controller
{
    public function __construct(
        private readonly IPlaylistRepository $playlistRepository,
        private readonly IPlaylistSongsRepository $playlistSongsRepository,
        private readonly ISongRepository $songRepository,
        private readonly PlaylistMapper $playlistMapper,
        private readonly SongMapper $songMapper,
        private readonly PlaylistService $playlistService,
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
    public function addSongToPlaylist(int $artistId, int $albumId, int $songId, int $playlistId): JsonResponse
    {
        $this->playlistSongsRepository->addSongToPlaylist($songId, $playlistId);
        return response()->json();
    }

    /**
     * @throws PlaylistSongsException
     */
    public function deleteSongsFromPlaylist(int $artistId, int $albumId, int $songId, int $playlistId): JsonResponse
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

        $playlistId = $this->playlistService->savePlaylist($data->name, $data->photo);

        return response()->json([
            "created playlist with id = {$playlistId}"
        ]);
    }

    /**
     * @throws DataAccessException
     * @throws MinioException
     */
    public function update(int $playlistId, UpdatePlaylistRequest $request): JsonResponse
    {
        $data = $request->body();

        $this->playlistService->updatePlaylist($playlistId, $data->name, $data->photo);

        return response()->json();
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
