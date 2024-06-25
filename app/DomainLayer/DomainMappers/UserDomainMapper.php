<?php

declare(strict_types = 1);

namespace App\DomainLayer\DomainMappers;

use App\DataAccessLayer\DbModels\BaseModel;
use App\DataAccessLayer\DbModels\User;
use App\DataAccessLayer\Repository\Interfaces\IArtistRepository;
use App\DomainLayer\DomainModels\ArtistDomain;
use App\DomainLayer\DomainModels\DomainModel;
use App\DomainLayer\DomainModels\UserDomain;
use App\DomainLayer\Enums\UserRoles;
use App\Exceptions\DataAccessExceptions\DataAccessException;

class UserDomainMapper
{
    public function __construct(
        private readonly IArtistRepository $artistRepository,
        private readonly ArtistDomainMapper $artistDomainMapper,
    ) {}

    /**
     * @throws DataAccessException
     */
    public function mapToDomain(User $model): UserDomain
    {
        return new UserDomain(
            id: $model->_id,
            name: $model->name,
            email: $model->email,
            favouriteArtistsIds: $model->favouriteArtistsIds ?? [],
            favouriteAlbumsIds: $model->favouriteAlbumsIds ?? [],
            favouriteSongsIds: $model->favouriteSongsIds ?? [],
            playlistsIds: $model->playlistsIds ?? [],
            role: UserRoles::from($model->role),
            artist: $this->getUserArtist($model)
        );
    }

    /**
     * @param User[] $models
     * @return UserDomain[]
     */
    public function mapMultipleToDomain(array $models): array
    {
        return array_map(fn ($model) => $this->mapToDomain($model), $models);
    }

    /**
     * @throws DataAccessException
     */
    private function getUserArtist(User $user): ?ArtistDomain
    {
        if ($user->role !== UserRoles::ArtistUser->value) {
            return null;
        }

        $artistDb = $this->artistRepository->getByUserId($user->_id);
        return $this->artistDomainMapper->mapToDomain($artistDb);
    }
}
