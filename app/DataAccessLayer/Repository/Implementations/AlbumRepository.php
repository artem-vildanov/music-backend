<?php

namespace App\DataAccessLayer\Repository\Implementations;

use App\DataAccessLayer\DbMappers\AlbumDbMapper;
use App\DataAccessLayer\DbModels\Album;
use App\DataAccessLayer\Repository\Interfaces\IAlbumRepository;
use App\Exceptions\DataAccessExceptions\AlbumException;
use Illuminate\Support\Facades\DB;
use MongoDB\BSON\ObjectId;

class AlbumRepository implements IAlbumRepository
{
    public function __construct(private readonly AlbumDbMapper $albumDbMapper) {}

    public function getById(string $albumId): Album
    {
        $album = Album::where('_id', new ObjectId($albumId))->first()
            ?? throw AlbumException::notFound($albumId);
        return $this->albumDbMapper->mapDbAlbum($album);
    }

    public function getAllByArtist(string $artistId): array
    {
        $albums = Album::where('artistId', new ObjectId($artistId))
            ->get()
            ->all();
        return $this->albumDbMapper->mapMultipleDbAlbums($albums);
    }

    public function getAllByGenre(string $genre)
    {
        // TODO: Implement getAllByGenre() method.
    }

    public function getAllReadyToPublish(): array
    {
        $albums = Album::where('publishAt', '<=', now())
            ->get()
            ->all();
        return $this->albumDbMapper->mapMultipleDbAlbums($albums);
    }

    public function create(
        string $name,
        string $photoPath,
        string $artistId,
        string $genre,
        ?string $publishTime,
    ): string {
        $album = new Album();
        $album->name = $name;
        $album->photoPath = $photoPath;
        $album->artistId = new ObjectId($artistId);
        $album->publishTime = $publishTime;
        $album->genre = $genre;
        $album->likes = 0;
        $album->cdnFolderId = uniqid(more_entropy: true);
        if (!$album->save()) {
            throw AlbumException::failedToCreate();
        }
        return $album->_id;
    }

    public function updateName(string $albumId, string $name): void
    {
        $result = Album::where('_id', new ObjectId($albumId))
            ->update(['name' => $name]);
        if ($result === 0) {
            throw AlbumException::failedToUpdate($albumId);
        }
    }

    public function makePublic(string $albumId): void
    {
        $result = Album::where('_id', new ObjectId($albumId))
            ->update(['publishTime' => null]);
        if ($result === 0) {
            throw AlbumException::failedToUpdate($albumId);
        }
    }

    public function updatePublishTime(string $albumId, string $publishTime): void
    {
        $result = Album::where('_id', new ObjectId($albumId))
            ->update(['publishTime' => $publishTime]);
        if ($result === 0) {
            throw AlbumException::failedToUpdate($albumId);
        }
    }

    public function updatePhoto(string $albumId, string $photoPath): void
    {
        $result = Album::where('_id', new ObjectId($albumId))
            ->update(['photoPath' => $photoPath]);
        if ($result === 0) {
            throw AlbumException::failedToUpdate($albumId);
        }
    }

    public function updateGenre(string $albumId, string $genre): void
    {
        $result = Album::where('_id', new ObjectId($albumId))
            ->update(['genre' => $genre]);
        if ($result === 0) {
            throw AlbumException::failedToUpdate($albumId);
        }
    }

    public function delete(string $albumId): void
    {
        $result = Album::destroy($albumId);
        if ($result === 0) {
            throw AlbumException::failedToDelete($albumId);
        }
    }

    public function incrementLikes(string $id): void
    {
        Album::where('_id', new ObjectId($id))
            ->update([
                '$inc' => ['likes' => 1]
            ]);
    }

    public function decrementLikes(string $id): void
    {
        Album::where('_id', new ObjectId($id))
            ->update([
                '$inc' => ['likes' => -1]
            ]);
    }
}
