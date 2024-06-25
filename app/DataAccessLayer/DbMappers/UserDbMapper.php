<?php

namespace App\DataAccessLayer\DbMappers;

use App\DataAccessLayer\DbModels\User;
use MongoDB\BSON\ObjectId;

class UserDbMapper
{
    /**
     * @param User[] $users
     * @return User[]
     */
    public function mapMultipleDbUsers(array $users): array
    {
        return array_map(
            fn ($user) => $this->mapDbUser($user),
            $users
        );
    }

    public function mapDbUser(User $user): User
    {
        $this->mapId($user);
        $this->mapForeignKeys($user);
        return $user;
    }

    private function mapId(User $user): void
    {
        $user->id = $user->_id;
        $user->_id = null;
    }

    private function mapForeignKeys(User $user): void
    {

        $user->favouriteSongsIds = array_map(
            fn ($objectId) => (string)$objectId,
            $user->favouriteSongsIds ?? []
        );

        $user->favouriteAlbumsIds = array_map(
            fn ($objectId) => (string)$objectId,
            $user->favouriteAlbumsIds ?? []
        );

        $user->favouriteArtistsIds = array_map(
            fn ($objectId) => (string)$objectId,
            $user->favouriteArtistsIds ?? []
        );

        $user->playlistsIds = array_map(
            fn ($objectId) => (string)$objectId,
            $user->playlistsIds ?? []
        );
    }

}
