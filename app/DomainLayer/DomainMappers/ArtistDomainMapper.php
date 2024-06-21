<?php

namespace App\DomainLayer\DomainMappers;

use App\DataAccessLayer\DbModels\Album;
use App\DataAccessLayer\DbModels\Artist;
use App\DataAccessLayer\DbModels\BaseModel;
use App\DataAccessLayer\Repository\Interfaces\IUserRepository;
use App\DomainLayer\DomainModels\AlbumDomain;
use App\DomainLayer\DomainModels\ArtistDomain;
use App\DomainLayer\DomainModels\UserDomain;
use App\Exceptions\DataAccessExceptions\DataAccessException;
use App\Facades\AuthFacade;

class ArtistDomainMapper
{
    private array $favouriteArtistsIds;

    /**
     * @throws DataAccessException
     */
    public function __construct(private readonly IUserRepository $userRepository)
    {
        $this->favouriteArtistsIds = $this->getFavoriteArtistsIds();
    }

    /**
     * @param Artist[] $models
     * @return ArtistDomain[]
     */
    public function mapMultipleToDomain(array $models): array
    {
        return array_map(fn (Artist $artist) => $this->mapToDomain($artist), $models);
    }

    public function mapToDomain(Artist $model): ArtistDomain
    {
        return new ArtistDomain(
            id: $model->_id,
            name: $model->name,
            likes: $model->likes,
            photoPath: $model->photoPath,
            userId: $model->userId,
            isFavourite: $this->checkArtistIsFavourite($model->_id),
            albumsIds: $model->albumsIds,
        );
    }

    private function checkArtistIsFavourite(string $artistId): bool
    {
        return in_array($artistId, $this->favouriteArtistsIds);
    }

    /**
     * @return string[]
     * @throws DataAccessException
     */
    private function getFavoriteArtistsIds(): array
    {
        $authUserId = AuthFacade::getUserId();
        return $this->userRepository->getById($authUserId)->favouriteArtistsIds;
    }
}
