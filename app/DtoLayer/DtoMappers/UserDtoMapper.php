<?php

namespace App\DtoLayer\DtoMappers;

use App\DomainLayer\DomainModels\ArtistDomain;
use App\DomainLayer\DomainModels\UserDomain;
use App\DtoLayer\LightDtoModels\ArtistLightDto;
use App\DtoLayer\LightDtoModels\UserLightDto;

class UserDtoMapper
{
    public function __construct(
        private readonly ArtistDtoMapper $artistDtoMapper,
    ) {}

    public function mapToLightDto(UserDomain $user): UserLightDto
    {
        return new UserLightDto(
            id: $user->id,
            name: $user->name,
            email: $user->email,
            role: $user->role->value,
            artist: $this->mapArtist($user->artist)
        );
    }

    private function mapArtist(?ArtistDomain $artist): ?ArtistLightDto
    {
        return $artist ? $this->artistDtoMapper->mapToLightDto($artist) : null;
    }

    /**
     * @param UserDomain[] $users
     * @return UserLightDto[]
     */
    public function mapMultipleToLightDto(array $users): array
    {

    }
}
