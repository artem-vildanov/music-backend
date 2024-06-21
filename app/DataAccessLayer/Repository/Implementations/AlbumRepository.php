<?php

namespace App\DataAccessLayer\Repository\Implementations;

use App\DataAccessLayer\DbModels\Album;
use App\DataAccessLayer\Repository\Interfaces\IAlbumRepository;
use App\Exceptions\DataAccessExceptions\AlbumException;
use Illuminate\Support\Facades\DB;

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

        $result = DB::table('albums')
            ->where('id', $albumId)
            ->update([
                'name' => $name,
                'genre_id' => $genreId
            ]);

        if ($result === 0) {
            throw AlbumException::failedToUpdate($albumId);
        }
    }

    public function makePublic(int $albumId): void
    {
        $result = DB::table('albums')
            ->where('id', $albumId)
            ->update([
                'status' => 'public',
                'publish_at' => null
            ]);

        if ($result === 0) {
            throw AlbumException::failedToUpdate($albumId);
        }

    }

    public function updatePublishTime(int $albumId, string $publishTime): void
    {
        $result = DB::table('albums')
            ->where('id', $albumId)
            ->update([
                'publish_time' => $publishTime
            ]);

        if ($result === 0) {
            throw AlbumException::failedToUpdate($albumId);
        }

    }

    public function updatePhoto(int $albumId, string $photoPath): void
    {
        $result = DB::table('albums')
            ->where('id', $albumId)
            ->update([
                'photo_path' => $photoPath
            ]);

        if ($result === 0) {
            throw AlbumException::failedToUpdate($albumId);
        }

    }

    public function delete(int $albumId): void
    {
        $result = DB::table('albums')
            ->where('id', $albumId)
            ->delete();

        if ($result === 0) {
            throw AlbumException::failedToDelete($albumId);
        }

    }
}
