<?php


namespace App\DataAccessLayer\Repository\Implementations;

use App\DataAccessLayer\DbMappers\ArtistDbMapper;
use App\DataAccessLayer\DbModels\Artist;
use App\DataAccessLayer\Repository\Interfaces\IArtistRepository;
use App\Exceptions\DataAccessExceptions\ArtistException;
use App\Services\CacheServices\ArtistCacheService;
use Illuminate\Support\Facades\DB;
use MongoDB\BSON\ObjectId;

class ArtistRepository
{
    public function __construct(private readonly ArtistDbMapper $artistDbMapper) {}

    public function getAll(): array
    {
        $artists = Artist::get()->all();
        return $this->artistDbMapper->mapMultipleDbArtists($artists);
    }

    public function getById(string $artistId): Artist
    {
        $artist = Artist::where('_id', new ObjectId($artistId))->first()
            ?? throw ArtistException::notFound($artistId);
        return $this->artistDbMapper->mapDbArtist($artist);
    }

    public function getByUserId(string $userId): Artist
    {
        $artist = Artist::where('userId', new ObjectId($userId))->first()
            ?? throw ArtistException::notFoundByUserId($userId);
        return $this->artistDbMapper->mapDbArtist($artist);
    }

    public function create(string $name, string $photoPath, string $userId): string
    {
        $artist = new Artist();
        $artist->name = $name;
        $artist->photoPath = $photoPath;
        $artist->userId = new ObjectId($userId);
        $artist->likes = 0;
        if (!$artist->save()) {
            throw ArtistException::failedToCreate();
        }

        return $artist->_id;
    }

    public function updateName(string $artistId, string $name): void
    {
        $result = Artist::where('_id', new ObjectId($artistId))
            ->update(['name' => $name]);
        if ($result === 0) {
            throw ArtistException::failedToUpdate($artistId);
        }
    }

    public function updatePhoto(string $artistId, string $photoPath): void
    {
        $result = Artist::where('_id', new ObjectId($artistId))
            ->update(['photoPath' => $photoPath]);
        if ($result === 0) {
            throw ArtistException::failedToUpdate($artistId);
        }
    }

    public function incrementLikes(string $id): void
    {
        Artist::where('_id', new ObjectId($id))
            ->update([
                '$inc' => ['likes' => 1]
            ]);
    }

    public function decrementLikes(string $id): void
    {
        Artist::where('_id', new ObjectId($id))
            ->update([
                '$inc' => ['likes' => -1]
            ]);
    }

    public function delete(string $artistId): void
    {
        $result = Artist::destroy($artistId);
        if ($result === 0) {
            throw ArtistException::failedToDelete($artistId);
        }
    }
}


