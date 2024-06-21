<?php

namespace App\DomainLayer\DomainMappers;

use App\DataAccessLayer\DbModels\BaseModel;
use App\DataAccessLayer\DbModels\Playlist;
use App\DomainLayer\DomainModels\PlaylistDomain;

class PlaylistDomainMapper
{

    public function mapToDomain(Playlist $model): PlaylistDomain
    {
        return new PlaylistDomain(
            id: $model->_id,
            name: $model->name,
            photoPath: $model->photoPath,
            userId: $model->userId,
        );
    }

    /**
     * @param Playlist[] $models
     * @return PlaylistDomain[]
     */
    public function mapMultipleToDomain(array $models): array
    {
        $playlistsDomainModels = [];
        foreach ($models as $model) {
            $playlistsDomainModels[] = $this->mapToDomain($model);
        }
        return $playlistsDomainModels;
    }
}
