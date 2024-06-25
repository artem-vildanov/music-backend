<?php

namespace App\DataAccessLayer\Repository\Proxies;

use App\DataAccessLayer\DbMappers\PlaylistDbMapper;
use App\DataAccessLayer\DbModels\Playlist;
use App\DataAccessLayer\Repository\Implementations\PlaylistRepository;
use App\DataAccessLayer\Repository\Interfaces\IPlaylistRepository;
use App\Exceptions\DataAccessExceptions\DataAccessException;
use App\Services\CacheServices\PlaylistCacheService;

class PlaylistRepositoryProxy extends PlaylistRepository implements IPlaylistRepository
{
    public function __construct(
        private readonly PlaylistCacheService $playlistCacheService,
        private readonly PlaylistRepository $playlistRepository,
        PlaylistDbMapper $playlistDbMapper
    ) {
        parent::__construct($playlistDbMapper);
    }

    /**
     * @throws DataAccessException
     */
    public function getById(string $playlistId): Playlist
    {
        $playlist = $this->playlistCacheService->getPlaylistFromCache($playlistId);

        if (!$playlist) {
            $playlist = $this->playlistRepository->getById($playlistId);
            $this->playlistCacheService->savePlaylistToCache($playlist);
        }

        return $playlist;
    }

    /**
     * @throws DataAccessException
     */
    public function updateName(string $playlistId, string $name): void
    {
        $this->playlistRepository->updateName($playlistId, $name);
        $this->playlistCacheService->deletePlaylistFromCache($playlistId);
    }

    public function updatePhoto(string $playlistId, string $photoPath): void
    {
        $this->playlistRepository->updatePhoto($playlistId, $photoPath);
        $this->playlistCacheService->deletePlaylistFromCache($playlistId);
    }

    /**
     * @throws DataAccessException
     */
    public function delete(string $playlistId): void
    {
        $this->playlistRepository->delete($playlistId);
        $this->playlistCacheService->deletePlaylistFromCache($playlistId);
    }
}
