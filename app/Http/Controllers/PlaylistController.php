<?php

namespace App\Http\Controllers;

use App\DataAccessLayer\Repository\Interfaces\IPlaylistRepository;
use App\DataAccessLayer\Repository\Interfaces\IPlaylistSongsRepository;
use App\DataAccessLayer\Repository\Interfaces\ISongRepository;
use App\DomainLayer\DomainMappers\PlaylistDomainMapper;
use App\DomainLayer\DomainMappers\SongDomainMapper;
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
        private readonly IPlaylistRepository  $playlistRepository,
        private readonly ISongRepository      $songRepository,
        private readonly PlaylistDtoMapper    $playlistDtoMapper,
        private readonly SongDtoMapper        $songDtoMapper,
        private readonly PlaylistDomainMapper $playlistDomainMapper,
        private readonly SongDomainMapper     $songDomainMapper,
        private readonly PlaylistService      $playlistService,
    ) {}

    /**
     * @throws DataAccessException
     */
    public function show(string $playlistId): JsonResponse
    {
        $playlist = $this->playlistRepository->getById($playlistId);
        $playlistDomain = $this->playlistDomainMapper->mapToDomain($playlist);
        $playlistDto = $this->playlistDtoMapper->mapToLightDto($playlistDomain);

        return response()->json($playlistDto);
    }

    public function showSongsInPlaylist(string $playlistId): JsonResponse
    {
//        $songsIdsGroup = $this->playlistRepository->getSongsInPlaylist($playlistId);
//        $songsDbGroup = $this->songRepository->getMultipleByIds($songsIdsGroup);
//        $songsDomainGroup = $this->songDomainMapper->mapMultipleToDomain($songsDbGroup);
//        $songsDtoGroup = $this->songDtoMapper->mapMultipleToLightDto($songsDomainGroup);
//
//        return response()->json($songsDtoGroup);
    }

    public function showUserPlaylists(): JsonResponse
    {
        $userId = AuthFacade::getUserId();

        $playlistsDbGroup = $this->playlistRepository->getPlaylistsByUserId($userId);
        $playlistsDomainGroup = $this->playlistDomainMapper->mapMultipleToDomain($playlistsDbGroup);
        $playlistDtoCollection = $this->playlistDtoMapper->mapMultipleToLightDto($playlistsDomainGroup);

        return response()->json($playlistDtoCollection);
    }

    public function addSongToPlaylist(string $playlistId, string $songId): JsonResponse
    {
        $this->playlistRepository->addSongToPlaylist($playlistId, $songId);
        return response()->json();
    }

    public function deleteSongsFromPlaylist(string $playlistId, string $songId): JsonResponse
    {
        $this->playlistRepository->removeSongFromPlaylist($playlistId, $songId);
        return response()->json();
    }

    /**
     * @throws DataAccessException
     */
    public function create(CreatePlaylistRequest $request): JsonResponse
    {
        $data = $request->body();
        $authUserId = AuthFacade::getUserId();
        $playlistId = $this->playlistRepository->create($data->name, $authUserId);
        return response()->json($playlistId);
    }

    /**
     * @throws DataAccessException
     */
    public function updateName(string $playlistId, UpdatePlaylistNameRequest $request): JsonResponse
    {
        $newName = $request->body();
        $this->playlistRepository->updateName($playlistId, $newName);
        return response()->json();
    }

    /**
     * @throws DataAccessException
     * @throws MinioException
     */
    public function updatePhoto(string $playlistId, UpdatePlaylistPhotoRequest $request): JsonResponse
    {
        $newPhoto = $request->body();
        $this->playlistService->updatePlaylistPhoto($playlistId, $newPhoto);
        return response()->json();
    }

    /**
     * @throws DataAccessException
     * @throws MinioException
     */
    public function delete(string $playlistId): JsonResponse
    {
        $this->playlistService->deletePlaylist($playlistId);
        return response()->json();
    }
}
