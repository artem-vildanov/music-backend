<?php

namespace App\DtoLayer\DtoMappers;



use App\DomainLayer\DomainModels\SongDomain;
use App\DtoLayer\BigDtoModels\SongBigDto;
use App\DtoLayer\LightDtoModels\SongLightDto;

class SongDtoMapper
{
    public function mapToBigDto(SongDomain $song): SongBigDto
    {

    }

    public function mapToLightDto(SongDomain $song): SongLightDto
    {

    }

    /**
     * @param SongDomain[] $songs
     * @return SongLightDto[]
     */
    public function mapMultipleToLightDto(array $songs): array
    {

    }
}
