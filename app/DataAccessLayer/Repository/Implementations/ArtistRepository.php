<?php


namespace App\DataAccessLayer\Repository\Implementations;

use App\DataAccessLayer\DbModels\Artist;
use App\DataAccessLayer\Repository\Interfaces\IArtistRepository;
use App\Exceptions\DataAccessExceptions\ArtistException;
use App\Services\CacheServices\ArtistCacheService;
use Illuminate\Support\Facades\DB;

class ArtistRepository
{
    public function getAll(): array
    {
        return Artist::get()->all();
    }

    public function getById(string $artistId): Artist
    {
        return Artist::where('_id', $artistId)->first() ?? throw ArtistException::notFound($artistId);
    }

    public function getMultipleByIds(array $artistIds): array
    {
        return Artist::where('_id', $artistIds)->get()->all();
    }

    public function getByUserId(string $userId): Artist
    {
        return Artist::where('userId', $userId)->first() ?? throw ArtistException::notFoundByUserId($userId);
    }

    public function create(string $name, string $photoPath, string $userId): string
    {
        $artist = new Artist();
        $artist->name = $name;
        $artist->photoPath = $photoPath;
        $artist->userId = $userId;
        $artist->likes = 0;
        if (!$artist->save()) {
            throw ArtistException::failedToCreate();
        }

        return $artist->_id;
    }

    public function updateName(string $artistId, string $name): void
    {
        $result = Artist::where('_id', $artistId)->update(['name' => $name]);
        if ($result === 0) {
            throw ArtistException::failedToUpdate($artistId);
        }
    }

    public function updatePhoto(string $artistId, string $photoPath): void
    {
        $result = Artist::where('_id', $artistId)->update(['photoPath' => $photoPath]);
        if ($result === 0) {
            throw ArtistException::failedToUpdate($artistId);
        }
    }

    public function incrementLikes(string $id): void
    {
        Artist::where('_id', $id)
            ->update([
                '$inc' => ['likes' => 1]
            ]);
    }

    public function decrementLikes(string $id): void
    {
        Artist::where('_id', $id)
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


