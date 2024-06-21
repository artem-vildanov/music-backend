<?php

namespace App\DtoLayer\LightDtoModels;

class ArtistLightDto
{
    public function __construct(
        public string $id,
        public string $name,
        public string $photoPath,
        public bool $isFavourite,
    ) {}
}
