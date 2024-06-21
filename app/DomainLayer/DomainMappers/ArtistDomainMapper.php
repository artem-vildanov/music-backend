<?php

namespace App\DomainLayer\DomainMappers;

use App\DataAccessLayer\DbModels\Album;
use App\DataAccessLayer\DbModels\Artist;
use App\DataAccessLayer\DbModels\BaseModel;
use App\DataAccessLayer\Repository\Interfaces\IUserRepository;
use App\DomainLayer\DomainModels\AlbumDomain;
use App\DomainLayer\DomainModels\ArtistDomain;
use App\DomainLayer\DomainModels\UserDomain;
use App\Facades\AuthFacade;

class ArtistDomainMapper
{
    /**
     * @param Artist[] $models
     * @return ArtistDomain[]
     */
    public function mapMultipleToDomain(array $models): array
    {
        $artistsDomainModels = [];
        foreach ($models as $model) {
            $artistsDomainModels[] = $this->mapToDomain($model);
        }
        return $artistsDomainModels;
    }

    public function mapToDomain(Artist $model): ArtistDomain
    {
        return new ArtistDomain(
            id: $model->_id,
            name: $model->name,
            likes: $model->likes,
            photoPath: $model->photoPath,
            userId: AuthFacade::getUserId(),
            albumsIds: $model->albumsIds,
        );
    }
}
