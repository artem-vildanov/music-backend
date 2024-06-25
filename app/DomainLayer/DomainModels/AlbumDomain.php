<?php

namespace App\DomainLayer\DomainModels;

use App\DomainLayer\Enums\Genres;

class AlbumDomain
{
    public function  __construct(
        public string $id,
        public string $name,
        public string $photoPath,
        public int $likes,
        public bool $isFavourite,
        public ?string $publishTime,
        public string $artistId,
        public string $artistName,
        public Genres $genre,
    ) {}
}
