<?php

namespace App\DtoLayer\DtoMappers;

use App\DataAccessLayer\DbModels\Playlist;
use App\DtoLayer\BigDtoModels\PlaylistBigDto;
use App\DtoLayer\LightDtoModels\PlaylistLightDto;
use App\DomainLayer\DomainModels\PlaylistDomain;

class PlaylistDtoMapper
{
    public function mapToBigDto(PlaylistDomain $playlist): PlaylistBigDto
    {

    }

    public function mapToLightDto(PlaylistDomain $playlist): PlaylistLightDto
    {

    }

    /**
     * @param PlaylistDomain[] $playlists
     * @return PlaylistLightDto[]
     */
    public function mapMultipleToLightDto(array $playlists): array
    {

    }
}
