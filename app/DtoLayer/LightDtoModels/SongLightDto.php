<?php

namespace App\DtoLayer\LightDtoModels;

class SongLightDto
{
    public function __construct(
        public string $id,
        public string $name,
        public string $photoPath,
        public string $musicPath,
        public bool $isFavourite,
    ) {}
}
