<?php

namespace App\DtoLayer\BigDtoModels;

class ArtistBigDto
{
    public function __construct(
        public string $id,
        public string $name,
        public string $photoPath,
        public int $likes,
        public string $userId,
        public bool $isFavourite,
        public array $albumsIds,
    ) {}
}
