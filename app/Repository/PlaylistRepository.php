<?php

namespace App\Repository;

use App\Exceptions\DataAccessExceptions\DataAccessException;
use App\Exceptions\DataAccessExceptions\PlaylistException;
use App\Models\Playlist;
use App\Services\CacheServices\PlaylistCacheService;

class PlaylistRepository implements Interfaces\IPlaylistRepository
{
    public function __construct(
        private readonly PlaylistCacheService $playlistCacheService
    ) {}

    /**
     * @throws DataAccessException
     */
    public function getById(int $playlistId): Playlist
    {
        $playlist = $this->playlistCacheService->getPlaylistFromCache($playlistId);

        if ($playlist) {
            return $playlist;
        }

        $playlist = Playlist::query()->find($playlistId);
        if (!$playlist) {
            throw PlaylistException::notFound($playlistId);
        }

        $this->playlistCacheService->savePlaylistToCache($playlist);

        return $playlist;
    }

    /**
     * @inheritDoc
     */
    public function getMultipleByIds(array $playlistsIds): array
    {
        return Playlist::query()->whereIn('id', $playlistsIds)->get()->all();
    }

    /**
     * @inheritDoc
     */
    public function getPlaylistsModelsByUserId(int $userId): array
    {
        return Playlist::query()->where('user_id', $userId)->get()->all();
    }

    public function getPlaylistsIdsByUserId(int $userId): array
    {
        return Playlist::query()
            ->where('user_id', $userId)
            ->pluck('id')
            ->toArray();
    }

    /**
     * @inheritDoc
     * @throws DataAccessException
     */
    public function create(string $name, string $photoPath, int $userId): int
    {
        $playlist = new Playlist();
        $playlist->name = $name;
        $playlist->photo_path = $photoPath;
        $playlist->user_id = $userId;

        if (!$playlist->save()) {
            throw PlaylistException::failedToCreate();
        }

        $this->playlistCacheService->savePlaylistToCache($playlist);

        return $playlist->id;
    }

    /**
     * @throws DataAccessException
     */
    public function update(int $playlistId, string $name): void
    {
        try {
            $playlist = $this->getById($playlistId);
        } catch (DataAccessException $e) {
            throw PlaylistException::failedToUpdate($playlistId);
        }

        $playlist->name = $name;

        if (!$playlist->save()) {
            throw PlaylistException::failedToUpdate($playlistId);
        }

        $this->playlistCacheService->deletePlaylistFromCache($playlistId);
    }

    /**
     * @throws DataAccessException
     */
    public function delete(int $playlistId): void
    {
        try {
            $playlist = $this->getById($playlistId);
        } catch (DataAccessException $e) {
            throw PlaylistException::failedToDelete($playlistId);
        }

        if (!$playlist->delete()) {
            throw PlaylistException::failedToDelete($playlistId);
        }

        $this->playlistCacheService->deletePlaylistFromCache($playlistId);
    }
}
