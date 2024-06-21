<?php

namespace App\DataAccessLayer\Repository\Implementations;

use App\DataAccessLayer\DbModels\Playlist;
use App\DataAccessLayer\Repository\Interfaces\IPlaylistRepository;
use App\Exceptions\DataAccessExceptions\DataAccessException;
use App\Exceptions\DataAccessExceptions\PlaylistException;
use Illuminate\Support\Facades\DB;

class PlaylistRepository implements IPlaylistRepository
{
    /**
     * @throws DataAccessException
     */
    public function getById(int $playlistId): Playlist
    {
        $playlist = Playlist::query()->find($playlistId);
        if (!$playlist) {
            throw PlaylistException::notFound($playlistId);
        }

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
    public function create(string $name, int $userId): int
    {
        $playlist = new Playlist();
        $playlist->name = $name;
        $playlist->user_id = $userId;
        $playlist->photo_path = "playlist/base_playlist_image.png";

        if (!$playlist->save()) {
            throw PlaylistException::failedToCreate();
        }

        return $playlist->id;
    }

    /**
     * @throws DataAccessException
     */
    public function updateName(int $playlistId, string $name): void
    {
        $result = DB::table('playlists')
            ->where('id', $playlistId)
            ->update([
                'name' => $name
            ]);

        if ($result === 0) {
            throw PlaylistException::failedToUpdate($playlistId);
        }

    }

    public function updatePhoto(int $playlistId, string $photoPath): void
    {
        $result = DB::table('playlists')
            ->where('id', $playlistId)
            ->update([
                'photo_path' => $photoPath
            ]);

        if ($result === 0) {
            throw PlaylistException::failedToUpdate($playlistId);
        }
    }

    /**
     * @throws DataAccessException
     */
    public function delete(int $playlistId): void
    {
        $result = DB::table('playlists')
            ->where('id', $playlistId)
            ->delete();

        if ($result === 0) {
            throw PlaylistException::failedToDelete($playlistId);
        }

    }
}
