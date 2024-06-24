<?php

namespace App\DtoLayer\DtoMappers;

use App\DomainLayer\DomainModels\PlaylistDomain;
use App\DtoLayer\LightDtoModels\PlaylistLightDto;

class PlaylistDtoMapper
{
    public function mapToLightDto(PlaylistDomain $playlist): PlaylistLightDto
    {
        return new PlaylistLightDto(
            id: $playlist->id,
            name: $playlist->name,
            photoPath: $playlist->photoPath,
        );
    }

    /**
     * @param PlaylistDomain[] $playlists
     * @return PlaylistLightDto[]
     */
    public function mapMultipleToLightDto(array $playlists): array
    {
        return array_map(fn (PlaylistDomain $playlist) => $this->mapToLightDto($playlist), $playlists);
    }
}
