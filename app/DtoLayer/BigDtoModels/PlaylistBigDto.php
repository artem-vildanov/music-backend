<?php

namespace App\DtoLayer\BigDtoModels;

class PlaylistBigDto
{
    public function __construct(
        public string $id,
        public string $name,
        public string $photoPath,
        /** @var string[] */
        public array $songsIds
    ) {}
}
