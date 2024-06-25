<?php

namespace App\DataAccessLayer\Repository\Implementations;

use App\DataAccessLayer\DbMappers\PlaylistDbMapper;
use App\DataAccessLayer\DbModels\Playlist;
use App\DataAccessLayer\Repository\Interfaces\IPlaylistRepository;
use App\Exceptions\DataAccessExceptions\DataAccessException;
use App\Exceptions\DataAccessExceptions\PlaylistException;
use Illuminate\Support\Facades\DB;
use MongoDB\BSON\ObjectId;

class PlaylistRepository implements IPlaylistRepository
{
    public function __construct(private readonly PlaylistDbMapper $playlistDbMapper) {}

    public function getById(string $playlistId): Playlist
    {
        $playlist = Playlist::where('_id', new ObjectId($playlistId))->first()
            ?? throw PlaylistException::notFound($playlistId);
        return $this->playlistDbMapper->mapDbPlaylist($playlist);
    }

    public function getPlaylistsByUserId(string $userId): array
    {
        $playlists = Playlist::where('userId', $userId)
            ->get()
            ->toArray();
        return $this->playlistDbMapper->mapMultipleDbPlaylists($playlists);
    }

//    // TODO remake by using aggregation
//    public function getSongsInPlaylist(string $playlistId): array
//    {
//        return Playlist::where('_id', $playlistId)
//            ->project(['songsIds' => 1, '_id' => 0])
//            ->first()
//            ->songsIds;
//    }

    /**
     * @inheritDoc
     * @throws DataAccessException
     */
    public function create(string $name, string $userId): string
    {
        $playlist = new Playlist();
        $playlist->name = $name;
        $playlist->userId = new ObjectId($userId);
        $playlist->photoPath = "playlist/base_playlist_image.png";

        if (!$playlist->save()) {
            throw PlaylistException::failedToCreate();
        }

        return $playlist->_id;
    }

    /**
     * @throws DataAccessException
     */
    public function updateName(string $playlistId, string $name): void
    {
        $result = Playlist::where('_id', new ObjectId($playlistId))->update(['name' => $name]);
        if ($result === 0) {
            throw PlaylistException::failedToUpdate($playlistId);
        }
    }

    public function updatePhoto(string $playlistId, string $photoPath): void
    {
        $result = Playlist::where('_id', new ObjectId($playlistId))->update(['photoPath' => $photoPath]);
        if ($result === 0) {
            throw PlaylistException::failedToUpdate($playlistId);
        }

    }

    public function addSongToPlaylist(string $playlistId, string $songId): void
    {
        Playlist::where('_id', new ObjectId($playlistId))->push('songsIds', new ObjectId($songId));
    }

    public function removeSongFromPlaylist(string $playlistId, string $songId): void
    {
        Playlist::where('_id', new ObjectId($playlistId))->pull('songsIds', new ObjectId($songId));
    }

    public function removeSongFromAllPlaylists(string $songId): void
    {
        Playlist::where('songsIds', new ObjectId($songId))
            ->update([
                '$pull' => ['songsIds' => new ObjectId($songId)]
            ]);
    }

    /**
     * @throws DataAccessException
     */
    public function delete(string $playlistId): void
    {
        $result = Playlist::destroy($playlistId);
        if ($result === 0) {
            throw PlaylistException::failedToDelete($playlistId);
        }
    }
}
