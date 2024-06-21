<?php

namespace App\DtoLayer\DtoMappers;

use App\DomainLayer\DomainModels\AlbumDomain;
use App\DtoLayer\BigDtoModels\AlbumBigDto;
use App\DtoLayer\LightDtoModels\AlbumLightDto;

class AlbumDtoMapper
{
    public function mapToBigDto(AlbumDomain $album): AlbumBigDto
    {

    }

    public function mapToLightDto(AlbumDomain $album): AlbumLightDto
    {

    }

    /**
     * @param AlbumDomain[] $albums
     * @return AlbumLightDto[]
     */
    public function mapMultipleToLightDto(array $albums): array
    {

    }
}
