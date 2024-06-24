<?php


namespace App\DataAccessLayer\Repository\Implementations;

use App\DataAccessLayer\DbModels\User;
use App\DataAccessLayer\Repository\Interfaces\IUserRepository;
use App\Exceptions\DataAccessExceptions\DataAccessException;
use App\Exceptions\DataAccessExceptions\UserException;
use App\Utils\Enums\UserRoles;

class UserRepository implements IUserRepository
{
    public function getById(string $userId): User
    {
        return User::where('_id', $userId)->first() ?? throw UserException::notFound($userId);
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
        return User::where('email', $email)->first() ?? throw UserException::notFoundByEmail($email);
    }

    public function delete(string $userId): void
    {
        // TODO: Implement delete() method.
    }

    public function update(string $userId, string $name, string $email, UserRoles $role): void
    {
        $result = User::where('_id', $userId)
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
        return User::where('_id', $userId)
            ->where('favouriteSongsIds', $songId)
            ->exists();
    }

    public function checkAlbumFavourite(string $userId, string $albumId): bool
    {
        return User::where('_id', $userId)
            ->where('favouriteAlbumsIds', $albumId)
            ->exists();
    }

    public function checkArtistFavourite(string $userId, string $artistId): bool
    {
        return User::where('_id', $userId)
            ->where('favouriteArtistsIds', $artistId)
            ->exists();
    }

    /**
     * ADD TO FAVOURITES
     */

    public function addArtistToFavourites(string $userId, string $artistId): void
    {
        User::where('_id', $userId)->push('favouriteArtistsIds', $artistId);
    }

    public function addAlbumToFavourites(string $userId, string $albumId): void
    {
        User::where('_id', $userId)->push('favouriteAlbumsIds', $albumId);
    }

    public function addSongToFavourites(string $userId, string $songId): void
    {
        User::where('_id', $userId)->push('favouriteSongsIds', $songId);
    }

    /**
     * DELETE FROM FAVOURITES
     */

    public function removeArtistFromFavourites(string $userId, string $artistId): void
    {
        User::where('_id', $userId)->pull('favouriteArtistsIds', $artistId);
    }

    public function removeAlbumFromFavourites(string $userId, string $albumId): void
    {
        User::where('_id', $userId)->pull('favouriteAlbumsIds', $albumId);
    }

    public function removeSongFromFavourites(string $userId, string $songId): void
    {
        User::where('_id', $userId)->pull('favouriteSongsIds', $songId);
    }

    /**
     * DELETE FROM ALL FAVOURITES
     */

    public function removeSongFromAllUsers(string $songId): void
    {
        User::where('favouriteSongsIds', $songId)
            ->update([
                '$pull' => ['favouriteSongsIds' => $songId]
            ]);
    }

    public function removeAlbumFromAllUsers(string $albumId): void
    {
        User::where('favouriteAlbumsIds', $albumId)
            ->update([
                '$pull' => ['favouriteAlbumsIds' => $albumId]
            ]);
    }

    public function removeArtistFromAllUsers(string $artistId): void
    {
        User::where('favouriteArtistsIds', $artistId)
            ->update([
                '$pull' => ['favouriteArtistsIds' => $artistId]
            ]);
    }

    /**
     * GET FAVOURITES
     */

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
}


