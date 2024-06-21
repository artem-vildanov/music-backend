<?php

namespace App\DomainLayer\DomainMappers;

use App\DataAccessLayer\DbModels\BaseModel;
use App\DataAccessLayer\DbModels\User;
use App\DomainLayer\DomainModels\DomainModel;
use App\DomainLayer\DomainModels\UserDomain;

class UserDomainMapper
{

    public function mapToDomain(User $model): UserDomain
    {
        return new UserDomain(
            id: $model->_id,
            name: $model->name,
            email: $model->email,
            favouriteArtistsIds: $model->favouriteArtistsIds,
            favouriteAlbumsIds: $model->favouriteAlbumsIds,
            favouriteSongsIds: $model->favouriteSongsIds,
            playlistsIds: $model->playlistsIds,
            role: $model->role,
        );
    }

    /**
     * @param User[] $models
     * @return UserDomain[]
     */
    public function mapMultipleToDomain(array $models): array
    {
        return array_map(fn (BaseModel $model) => $this->mapToDomain($model), $models);
    }
}
