<?php


namespace App\DataAccessLayer\Repository\Implementations;

use App\DataAccessLayer\DbMappers\UserDbMapper;
use App\DataAccessLayer\DbModels\User;
use App\DataAccessLayer\Repository\Interfaces\IUserRepository;
use App\DomainLayer\Enums\UserRoles;
use App\Exceptions\DataAccessExceptions\DataAccessException;
use App\Exceptions\DataAccessExceptions\UserException;
use MongoDB\BSON\ObjectId;

class UserRepository implements IUserRepository
{
    public function __construct(private readonly UserDbMapper $userDbMapper) {}

    public function getById(string $userId): User
    {
        $user = User::where('_id', new ObjectId($userId))->first()
            ?? throw UserException::notFound($userId);
        return $this->userDbMapper->mapDbUser($user);
    }

    public function create(string $name, string $password, string $email, UserRoles $role): User
    {
        $user = new User;
        $user->name = $name;
        $user->password = $password;
        $user->email = $email;
        $user->role = $role->value;
        $user->save();

        return $user;
    }

    /**
     * @throws DataAccessException
     */
    public function getByEmail(string $email): User
    {
        return User::where('email', $email)->first()
            ?? throw UserException::notFoundByEmail($email);
    }

    public function delete(string $userId): void
    {
        // TODO: Implement delete() method.
    }

    public function update(string $userId, string $name, string $email, UserRoles $role): void
    {
        $result = User::where('_id', new ObjectId($userId))
            ->update([
                'name' => $name,
                'email' => $email,
                'role' => $role->value
            ]);
        if ($result === 0) {
            throw UserException::failedToUpdate($userId);
        }
    }

    /**
     * FAVOURITE CHECK
     */

    public function checkSongFavourite(string $userId, string $songId): bool
    {
        return User::where('_id', new ObjectId($userId))
            ->where('favouriteSongsIds', new ObjectId($songId))
            ->exists();
    }

    public function checkAlbumFavourite(string $userId, string $albumId): bool
    {
        return User::where('_id', new ObjectId($userId))
            ->where('favouriteAlbumsIds', new ObjectId($albumId))
            ->exists();
    }

    public function checkArtistFavourite(string $userId, string $artistId): bool
    {
        return User::where('_id', new ObjectId($userId))
            ->where('favouriteArtistsIds', new ObjectId($artistId))
            ->exists();
    }

    /**
     * ADD TO FAVOURITES
     */

    public function addArtistToFavourites(string $userId, string $artistId): void
    {
        User::where('_id', new ObjectId($userId))
            ->push(
                'favouriteArtistsIds',
                new ObjectId($artistId)
            );
    }

    public function addAlbumToFavourites(string $userId, string $albumId): void
    {
        User::where('_id', new ObjectId($userId))
            ->push(
                'favouriteAlbumsIds',
                new ObjectId($albumId)
            );
    }

    public function addSongToFavourites(string $userId, string $songId): void
    {
        User::where('_id', new ObjectId($userId))
            ->push(
                'favouriteSongsIds',
                new ObjectId($songId)
            );
    }

    /**
     * DELETE FROM FAVOURITES
     */

    public function removeArtistFromFavourites(string $userId, string $artistId): void
    {
        User::where('_id', new ObjectId($userId))
            ->pull('favouriteArtistsIds', new ObjectId($artistId));
    }

    public function removeAlbumFromFavourites(string $userId, string $albumId): void
    {
        User::where('_id', new ObjectId($userId))
            ->pull('favouriteAlbumsIds', new ObjectId($albumId));
    }

    public function removeSongFromFavourites(string $userId, string $songId): void
    {
        User::where('_id', new ObjectId($userId))
            ->pull('favouriteSongsIds', new ObjectId($songId));
    }

    /**
     * DELETE FROM ALL FAVOURITES
     */

    public function removeSongFromAllUsers(string $songId): void
    {
        User::where('favouriteSongsIds', new ObjectId($songId))
            ->update([
                '$pull' => ['favouriteSongsIds' => new ObjectId($songId)]
            ]);
    }

    public function removeAlbumFromAllUsers(string $albumId): void
    {
        User::where('favouriteAlbumsIds', new ObjectId($albumId))
            ->update([
                '$pull' => ['favouriteAlbumsIds' => new ObjectId($albumId)]
            ]);
    }

    public function removeArtistFromAllUsers(string $artistId): void
    {
        User::where('favouriteArtistsIds', new ObjectId($artistId))
            ->update([
                '$pull' => ['favouriteArtistsIds' => new ObjectId($artistId)]
            ]);
    }

    /**
     * GET FAVOURITES
     */

    /**
    public function getFavouriteArtists(string $userId): array
    {
        return User::where('_id', $userId)
            ->project(['favouriteArtistsIds' => 1, '_id' => 0])
            ->first()
            ->favouriteArtistsIds;
    }

    public function getFavouriteSongs(string $userId): array
    {
        return User::where('_id', $userId)
            ->project(['favouriteSongsIds' => 1, '_id' => 0])
            ->first()
            ->favouriteSongsIds;
    }

    public function getFavouriteAlbums(string $userId): array
    {
        return User::where('_id', $userId)
            ->project(['favouriteAlbumsIds' => 1, '_id' => 0])
            ->first()
            ->favouriteAlbumsIds;
    }
    */
}
