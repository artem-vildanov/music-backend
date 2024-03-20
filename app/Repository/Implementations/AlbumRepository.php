<?php

namespace App\Repository\Implementations;

use App\Exceptions\DataAccessExceptions\AlbumException;
use App\Exceptions\DataAccessExceptions\DataAccessException;
use App\Models\Album;
use App\Repository\Interfaces\IAlbumRepository;


class AlbumRepository implements IAlbumRepository
{
    public function getById(int $albumId): Album
    {
        $album = Album::query()->find($albumId);

        if (!$album) {
            throw AlbumException::notFound($albumId);
        }

        return $album;
    }

    public function getMultipleByIds(array $albumsIds): array
    {
        return Album::query()->whereIn('id', $albumsIds)->get()->all();
    }

    public function getAllByArtist(int $artistId): array
    {
        return Album::query()->where('artist_id', $artistId)->get()->all();
    }

    public function getAllByGenre(int $genreId)
    {
        // TODO: Implement getAllByGenre() method.
    }

    public function create(
        string $name,
        string $photoPath,
        int $artistId,
        int $genreId
    ): int {
        $album = new Album;
        $album->name = $name;
        $album->photo_path = $photoPath;
        $album->artist_id = $artistId;
        $album->genre_id = $genreId;

        $album->likes = 0;
        $album->cdn_folder_id = uniqid(more_entropy: true);
        $album->status = 'private';
        $album->created_at = now();
        $album->updated_at = now();

        if (!$album->save()) {
            throw AlbumException::failedToCreate();
        }

        return $album->id;
    }

    public function update(
        int $albumId,
        string $name,
        string $status,
        int $genreId
    ): void {
        try {
            $album = $this->getById($albumId);
        } catch (DataAccessException $e) {
            throw AlbumException::failedToUpdate($albumId);
        }

        $album->name = $name;
        $album->status = $status;
        $album->genre_id = $genreId;

        if (!$album->save()) {
            throw AlbumException::failedToUpdate($albumId);
        }
    }

    public function delete(int $albumId): void
    {
        try {
            $album = $this->getById($albumId);
        } catch (DataAccessException $e) {
            throw AlbumException::failedToDelete($albumId);
        }

        if (!$album->delete()) {
            throw AlbumException::failedToDelete($albumId);
        }
    }
}
