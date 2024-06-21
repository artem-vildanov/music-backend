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
        return new PlaylistBigDto(
            id: $playlist->id,
            name: $playlist->name,
            photoPath: $playlist->photoPath,
            songsIds: $playlist->songsIds
        );
    }

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
