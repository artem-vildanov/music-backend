<?php

namespace App\DataAccessLayer\Repository\Implementations;

use App\DataAccessLayer\DbModels\Song;
use App\DataAccessLayer\Repository\Interfaces\ISongRepository;
use App\Exceptions\DataAccessExceptions\DataAccessException;
use App\Exceptions\DataAccessExceptions\SongException;
use Illuminate\Support\Facades\DB;

class SongRepository implements ISongRepository
{
    public function getById(string $songId): Song
    {
        return Song::where('songId', $songId)->first() ?? throw SongException::notFound($songId);
    }

    public function getMultipleByIds(array $songsIds): array
    {
        return Song::whereIn('id', $songsIds)->get()->all();
    }

    public function getAllByAlbum(string $albumId): array
    {
        return Song::where('albumId', $albumId)->get()->all();
    }

    public function create(
        string $name,
        string $photoPath,
        string $musicPath,
        string $albumId,
        string $artistId
    ): string {
        $song = new Song;
        $song->name = $name;
        $song->likes = 0;
        $song->photoPath = $photoPath;
        $song->musicPath = $musicPath;
        $song->albumId = $albumId;
        $song->artistId = $artistId;
        $song->save();
        return $song->_id;
    }

    public function delete(string $songId): void
    {
        $result = Song::destroy($songId);
        if ($result === 0) {
            throw SongException::failedToDelete($songId);
        }
    }

    public function updateName(string $songId, string $name): void
    {
        $result = Song::where('_id', $songId)->update(['name' => $name]);
        if ($result === 0) {
            throw SongException::failedToUpdate($songId);
        }
    }

    public function updatePhoto(string $songId, string $photoPath): void
    {
        $result = Song::where('_id', $songId)->update(['photoPath' => $photoPath]);
        if ($result === 0) {
            throw SongException::failedToUpdate($songId);
        }
    }

    public function updateAudio(string $songId, string $musicPath): void
    {
        $result = Song::where('_id', $songId)->update(['musicPath' => $musicPath]);
        if ($result === 0) {
            throw SongException::failedToUpdate($songId);
        }
    }

    public function incrementLikes(string $id): void
    {
        Song::where('_id', $id)
            ->update([
                '$inc' => ['likes' => 1]
            ]);
    }

    public function decrementLikes(string $id): void
    {
        Song::where('_id', $id)
            ->update([
                '$inc' => ['likes' => -1]
            ]);
    }
}
