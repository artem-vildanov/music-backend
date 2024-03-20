<?php

namespace App\Repository;

use App\Exceptions\DataAccessExceptions\DataAccessException;
use App\Exceptions\DataAccessExceptions\SongException;
use App\Models\Song;
use App\Repository\Interfaces\ISongRepository;
use App\Services\CacheServices\SongCacheService;

class SongRepository implements ISongRepository
{
    public function __construct(
        private readonly SongCacheService $songCacheService
    ) {}

    /**
     * @throws DataAccessException
     */
    public function getById(int $songId): Song
    {
        $song = $this->songCacheService->getSongFromCache($songId);
        if ($song) {
            return $song;
        }

        $song = Song::query()->find($songId);

        if (!$song) {
            throw SongException::notFound($songId);
        }

        $this->songCacheService->saveSongToCache($song);

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

        $this->songCacheService->saveSongToCache($song);

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

        $this->songCacheService->deleteSongFromCache($songId);
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

        $this->songCacheService->deleteSongFromCache($songId);
    }
}
