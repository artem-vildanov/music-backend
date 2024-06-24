<?php

namespace App\DtoLayer\LightDtoModels;

class UserLightDto
{
    public function __construct(
        public string $id,
        public string $name,
        public string $email,
        public string $role,
        public ?ArtistLightDto $artist,
    ) {}
}
