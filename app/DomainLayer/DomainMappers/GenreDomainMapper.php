<?php

namespace App\DomainLayer\DomainMappers;

use App\DataAccessLayer\DbModels\BaseModel;
use App\DataAccessLayer\DbModels\Genre;
use App\DomainLayer\DomainModels\DomainModel;
use App\DomainLayer\DomainModels\GenreDomain;

class GenreDomainMapper
{

    public function mapToDomain(Genre $model): GenreDomain
    {
        return new GenreDomain(
            id: $model->_id,
            name: $model->name,
            likes: $model->likes,
        );
    }

    /**
     * @param Genre[] $models
     * @return GenreDomain[]
     */
    public function mapMultipleToDomain(array $models): array
    {
        $genresDomainModels = [];
        foreach ($models as $model) {
            $genresDomainModels[] = $this->mapToDomain($model);
        }
        return $genresDomainModels;
    }
}
