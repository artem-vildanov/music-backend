<?php

namespace App\DtoLayer\LightDtoModels;

class PlaylistLightDto
{
    public function __construct(
        public string $id,
        public string $name,
        public string $photoPath
    ) {}
}
