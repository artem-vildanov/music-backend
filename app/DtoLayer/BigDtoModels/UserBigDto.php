<?php

namespace App\DtoLayer\BigDtoModels;

use App\DtoLayer\LightDtoModels\ArtistLightDto;

class UserBigDto
{
    public function __construct(
        public string $id,
        public string $name,
        public string $email,
        public array $favouriteArtistsIds,
        public array $favouriteAlbumsIds,
        public array $favouriteSongsIds,
        public array $playlistsIds,
        public string $role,
    ) {}
}
