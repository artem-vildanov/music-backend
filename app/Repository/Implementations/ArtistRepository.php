<?php


namespace App\Repository\Implementations;

use App\Exceptions\DataAccessExceptions\ArtistException;
use App\Exceptions\DataAccessExceptions\DataAccessException;
use App\Models\Artist;
use App\Repository\Interfaces\IArtistRepository;
use App\Services\CacheServices\ArtistCacheService;

class ArtistRepository implements IArtistRepository
{
    public function __construct(
        private readonly ArtistCacheService $artistCacheService
    ) {}

    public function getById(int $artistId): Artist
    {
        $artist = Artist::query()->find($artistId);
        if (!$artist) {
            throw ArtistException::notFound($artistId);
        }

        return $artist;
    }

    public function getMultipleByIds(array $artistIds): array
    {
        return Artist::query()->whereIn('id', $artistIds)->get()->all();
    }

    public function getByUserId(int $userId): Artist
    {
        $artist = Artist::query()->where('user_id', $userId)->first();
        if (!$artist) {
            throw ArtistException::notFoundByUserId($userId);
        }

        return $artist;
    }

    public function create(string $name, string $photoPath, int $userId): int
    {
        $artist = new Artist;

        $artist->name = $name;
        $artist->photo_path = $photoPath;
        $artist->user_id = $userId;
        $artist->likes = 0;
        $artist->created_at = now();
        $artist->updated_at = now();

        if (!$artist->save()) {
            throw ArtistException::failedToCreate();
        }

        return $artist->id;
    }

    public function updateName(int $artistId, string $name): void
    {
        try {
            $artist = $this->getById($artistId);
            $artist->name = $name;
            $artist->save();
        } catch (DataAccessException $e) {
            throw ArtistException::failedToUpdate($artistId);
        }
    }

    public function updatePhoto(int $artistId, string $photoPath): void 
    {
        try {
            $artist = $this->getById($artistId);
            $artist->photo_path = $photoPath;
            $artist->save();
        } catch (DataAccessException $e) {
            throw ArtistException::failedToUpdate($artistId);
        }
    }

    public function delete(int $artistId): void
    {
        try {
            $artist = $this->getById($artistId);
        } catch (DataAccessException $e) {
            throw ArtistException::failedToDelete($artistId);
        }

        if (!$artist->delete()) {
            throw ArtistException::failedToDelete($artistId);
        }
    }
}


