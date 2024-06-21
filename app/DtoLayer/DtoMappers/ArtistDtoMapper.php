<?php

namespace App\DtoLayer\DtoMappers;

use App\DomainLayer\DomainModels\ArtistDomain;
use App\DtoLayer\BigDtoModels\ArtistBigDto;
use App\DtoLayer\LightDtoModels\ArtistLightDto;

class ArtistDtoMapper
{
    public function mapToBigDto(ArtistDomain $artist): ArtistBigDto
    {

    }

    public function mapToLightDto(ArtistDomain $artist): ArtistLightDto
    {

    }

    /**
     * @param ArtistDomain[] $artists
     * @return ArtistLightDto[]
     */
    public function mapMultipleToLightDto(array $artists): array
    {

    }
}
