<?php

namespace App\DtoLayer\DtoMappers;

use App\DomainLayer\DomainModels\GenreDomain;
use App\DtoLayer\BigDtoModels\GenreBigDto;

class GenreDtoMapper
{
    public function mapToBigDto(GenreDomain $genre): GenreBigDto
    {

    }

    /**
     * @param GenreDomain[] $genres
     * @return GenreBigDto[]
     */
    public function mapMultipleToBigDto(array $genres): array
    {

    }
}
