<?php

namespace App\DtoLayer\DtoMappers;

use App\DomainLayer\DomainModels\ArtistDomain;
use App\DtoLayer\BigDtoModels\ArtistBigDto;
use App\DtoLayer\LightDtoModels\ArtistLightDto;

class ArtistDtoMapper
{
    public function mapToBigDto(ArtistDomain $artist): ArtistBigDto
    {
        return new ArtistBigDto(
            id: $artist->id,
            name: $artist->name,
            photoPath: $artist->photoPath,
            likes: $artist->likes,
            userId: $artist->userId,
            isFavourite: $artist->isFavourite,
        );
    }

    public function mapToLightDto(ArtistDomain $artist): ArtistLightDto
    {
        return new ArtistLightDto(
            id: $artist->id,
            name: $artist->name,
            photoPath: $artist->photoPath,
            isFavourite: $artist->isFavourite,
        );
    }

    /**
     * @param ArtistDomain[] $artists
     * @return ArtistLightDto[]
     */
    public function mapMultipleToLightDto(array $artists): array
    {
        return array_map(fn (ArtistDomain $artist) => $this->mapToLightDto($artist), $artists);
    }
}
