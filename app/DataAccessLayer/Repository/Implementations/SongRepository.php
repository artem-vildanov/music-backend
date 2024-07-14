<?php

namespace App\DataAccessLayer\Repository\Implementations;

use App\DataAccessLayer\DbMappers\SongDbMapper;
use App\DataAccessLayer\DbModels\Song;
use App\DataAccessLayer\Repository\Interfaces\ISongRepository;
use App\Exceptions\DataAccessExceptions\DataAccessException;
use App\Exceptions\DataAccessExceptions\SongException;
use Illuminate\Support\Facades\DB;
use MongoDB\BSON\ObjectId;

class SongRepository implements ISongRepository
{
    public function __construct(private readonly SongDbMapper $songDbMapper) {}

    public function getById(string $songId): Song
    {
        $song = Song::where('_id', new ObjectId($songId))->first()
            ?? throw SongException::notFound($songId);
        return $this->songDbMapper->mapDbSong($song);
    }

//    public function getMultipleByIds(array $songsIds): array
//    {
//        return Song::whereIn('id', $songsIds)->get()->all();
//    }

    public function getAllByAlbum(string $albumId): array
    {
        $songs = Song::where('albumId', new ObjectId($albumId))
            ->get()
            ->all();
        return $this->songDbMapper->mapMultipleDbSongs($songs);
    }

    public function create(
        string $name,
        string $photoPath,
        string $audioId,
        string $albumId,
        string $artistId
    ): string {
        $song = new Song;
        $song->name = $name;
        $song->likes = 0;
        $song->photoPath = $photoPath;
        $song->audioId = new ObjectId($audioId);
        $song->albumId = new ObjectId($albumId);
        $song->artistId = new ObjectId($artistId);
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
        $result = Song::where('_id', new ObjectId($songId))
            ->update(['name' => $name]);
        if ($result === 0) {
            throw SongException::failedToUpdate($songId);
        }
    }

    public function updatePhoto(string $songId, string $photoPath): void
    {
        $result = Song::where('_id', new ObjectId($songId))
            ->update(['photoPath' => $photoPath]);
        if ($result === 0) {
            throw SongException::failedToUpdate($songId);
        }
    }

    public function updateAudio(string $songId, string $musicPath): void
    {
        $result = Song::where('_id', new ObjectId($songId))
            ->update(['musicPath' => $musicPath]);
        if ($result === 0) {
            throw SongException::failedToUpdate($songId);
        }
    }

    public function incrementLikes(string $id): void
    {
        Song::where('_id', new ObjectId($id))
            ->update([
                '$inc' => ['likes' => 1]
            ]);
    }

    public function decrementLikes(string $id): void
    {
        Song::where('_id', new ObjectId($id))
            ->update([
                '$inc' => ['likes' => -1]
            ]);
    }

    public function update(string $songId, string $name, string $audioId): void
    {
        $result = Song::where('_id', new ObjectId($songId))
            ->update([
                'name' => $name,
                'audioId' => new ObjectId($audioId)
            ]);
        if ($result === 0) throw SongException::failedToUpdate($songId);
    }
}
