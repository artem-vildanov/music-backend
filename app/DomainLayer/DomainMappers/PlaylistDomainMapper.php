<?php

namespace App\DomainLayer\DomainMappers;

use App\DataAccessLayer\DbModels\BaseModel;
use App\DataAccessLayer\DbModels\Playlist;
use App\DomainLayer\DomainModels\PlaylistDomain;

class PlaylistDomainMapper
{
    /**
     * @param Playlist[] $models
     * @return PlaylistDomain[]
     */
    public function mapMultipleToDomain(array $models): array
    {
        return array_map(fn (Playlist $playlist) => $this->mapToDomain($playlist), $models);
    }

    public function mapToDomain(Playlist $model): PlaylistDomain
    {
        return new PlaylistDomain(
            id: $model->id,
            name: $model->name,
            photoPath: $this->mapPhotoPath($model->photoPath),
            userId: $model->userId,
        );
    }

    private function mapPhotoPath(string $photoPath): string
    {
        return config('minio.photoUrl') . $photoPath;
    }
}
