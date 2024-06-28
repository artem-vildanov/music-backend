<?php

namespace App\DtoLayer\DtoMappers;



use App\DomainLayer\DomainModels\SongDomain;
use App\DtoLayer\BigDtoModels\SongBigDto;
use App\DtoLayer\LightDtoModels\SongLightDto;

class SongDtoMapper
{
    /**
     * @param SongDomain[] $songs
     * @return SongLightDto[]
     */
    public function mapMultipleToLightDto(array $songs): array
    {
        return array_map(fn (SongDomain $song) => $this->mapToLightDto($song), $songs);
    }

    public function mapToBigDto(SongDomain $song): SongBigDto
    {
        return new SongBigDto(
            id: $song->id,
            name: $song->name,
            photoPath: $song->photoPath,
            musicPath: $song->musicPath,
            likes: $song->likes,
            albumId: $song->albumId,
            albumName: $song->albumName,
            artistId: $song->artistId,
            artistName: $song->artistName,
            isFavourite: $song->isFavourite,
        );
    }

    public function mapToLightDto(SongDomain $song): SongLightDto
    {
        return new SongLightDto(
            id: $song->id,
            name: $song->name,
            photoPath: $song->photoPath,
            musicPath: $song->musicPath,
            isFavourite: $song->isFavourite,
        );
    }
}
