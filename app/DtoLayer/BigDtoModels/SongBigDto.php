<?php

namespace App\DtoLayer\BigDtoModels;

class SongBigDto
{
    public function __construct(
        public string $id,
        public string $name,
        public string $photoPath,
        public string $musicPath,
        public int $likes,
        public string $albumId,
        public string $albumName,
        public string $artistId,
        public string $artistName,
        public bool $isFavourite,
    ) {}
}
