<?php

namespace App\Repository\Implementations;

use App\Exceptions\DataAccessExceptions\AlbumException;
use App\Exceptions\DataAccessExceptions\DataAccessException;
use App\Models\Album;
use App\Repository\Interfaces\IAlbumRepository;
use Illuminate\Support\Facades\Log;

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

    public function getAllReadyToPublish(): array
    {
        // try {
            
        // } catch (\Throwable $th) {
        //     Log::error($th);
        // }
        $albums = Album::query()->where('publish_at', '<=', now())->get()->all();
        return $albums;
    }

    public function create(
        string $name,
        string $photoPath,
        int $artistId,
        int $genreId,
        ?string $publishTime,
        string $status
    ): int {
        $album = new Album;
        $album->name = $name;
        $album->photo_path = $photoPath;
        $album->artist_id = $artistId;
        $album->genre_id = $genreId;
        $album->publish_at = $publishTime;
        $album->status = $status;
        $album->likes = 0;
        $album->cdn_folder_id = uniqid(more_entropy: true);
        
        $album->created_at = now();
        $album->updated_at = now();

        if (!$album->save()) {
            throw AlbumException::failedToCreate();
        }

        return $album->id;
    }

    public function updateNameAndGenre(
        int $albumId,
        string $name,
        int $genreId
    ): void {
        try {
            $album = $this->getById($albumId);
            $album->name = $name;
            $album->genre_id = $genreId;
            $album->save();
        } catch (DataAccessException $e) {
            throw AlbumException::failedToUpdate($albumId);
        }
    }

    public function makePublic(int $albumId): void
    {
        try {
            $album = $this->getById($albumId);
            $album->status = 'public';
            $album->publish_at = null; 
            $album->save();
        } catch (DataAccessException $e) {
            throw AlbumException::failedToUpdate($albumId);
        }
    }

    public function updatePublishTime(int $albumId, string $publishTime): void
    {
        try {
            $album = $this->getById($albumId);
            $album->publish_at = $publishTime; 
            $album->save();
        } catch (DataAccessException $e) {
            throw AlbumException::failedToUpdate($albumId);
        }
    }

    public function updatePhoto(int $albumId, string $photoPath): void
    {
        try {
            $album = $this->getById($albumId);
            $album->photo_path = $photoPath; 
            $album->save();
        } catch (DataAccessException $e) {
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
