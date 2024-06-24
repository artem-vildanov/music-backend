<?php

namespace App\DtoLayer\BigDtoModels;

class AlbumBigDto
{
    public function __construct(
        public string $id,
        public string $name,
        public string $photoPath,
        public int $likes,
        public string $artistId,
        public string $artistName,
        public string $genre,
        public bool $isFavourite,
        public ?string $publishTime,
    ) {}
}
