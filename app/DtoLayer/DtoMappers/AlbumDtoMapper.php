<?php

namespace App\DtoLayer\DtoMappers;

use App\DomainLayer\DomainModels\AlbumDomain;
use App\DtoLayer\BigDtoModels\AlbumBigDto;
use App\DtoLayer\LightDtoModels\AlbumLightDto;

class AlbumDtoMapper
{
    public function mapToBigDto(AlbumDomain $album): AlbumBigDto
    {
        return new AlbumBigDto(
            id: $album->id,
            name: $album->name,
            photoPath: $album->photoPath,
            likes: $album->likes,
            artistId: $album->artistId,
            artistName: $album->artistName,
            genre: $album->genre->value,
            isFavourite: $album->isFavourite,
            publishTime: $album->publishTime,
        );
    }

    public function mapToLightDto(AlbumDomain $album): AlbumLightDto
    {
        return new AlbumLightDto(
            id: $album->id,
            name: $album->name,
            artistId: $album->artistId,
            artistName: $album->artistName,
            photoPath: $album->photoPath,
            isFavourite: $album->isFavourite,
        );
    }

    /**
     * @param AlbumDomain[] $albums
     * @return AlbumLightDto[]
     */
    public function mapMultipleToLightDto(array $albums): array
    {
        return array_map(fn (AlbumDomain $album) => $this->mapToLightDto($album), $albums);
    }
}
