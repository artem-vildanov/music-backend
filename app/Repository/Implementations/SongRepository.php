<?php

namespace App\Repository\Implementations;

use App\Exceptions\DataAccessExceptions\DataAccessException;
use App\Exceptions\DataAccessExceptions\SongException;
use App\Models\Song;
use App\Repository\Interfaces\ISongRepository;

class SongRepository implements ISongRepository
{
    /**
     * @throws DataAccessException
     */
    public function getById(int $songId): Song
    {
        $song = Song::query()->find($songId);

        if (!$song) {
            throw SongException::notFound($songId);
        }

        return $song;
    }

    public function getMultipleByIds(array $songsIds): array
    {
        return Song::query()->whereIn('id', $songsIds)->get()->all();
    }

    public function getAllByAlbum(int $albumId): array
    {
        return Song::query()->where('album_id', $albumId)->get()->all();
    }

    public function create(string $name, string $photoPath, string $musicPath, int $albumId): int
    {
        $song = new Song;
        $song->name = $name;
        $song->likes = 0;
        $song->photo_path = $photoPath;
        $song->music_path = $musicPath;
        $song->album_id = $albumId;

        $song->save();

        return $song->id;
    }

    /**
     * @throws DataAccessException
     */
    public function delete(int $songId): void
    {
        try {
            $song = $this->getById($songId);
        } catch (DataAccessException $e) {
            throw SongException::failedToDelete($songId);
        }

        if (!$song->delete()) {
            throw SongException::failedToDelete($songId);
        }
    }

    /**
     * @throws DataAccessException
     */
    public function update(int $songId, string $name): void
    {
        try {
            $song = $this->getById($songId);
        } catch (DataAccessException $e) {
            throw SongException::failedToUpdate($songId);
        }

        $song->name = $name;

        if (!$song->save()) {
            throw SongException::failedToUpdate($songId);
        }
    }
}
