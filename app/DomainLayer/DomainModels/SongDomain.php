<?php

namespace App\DomainLayer\DomainModels;

class SongDomain
{
    public function __construct(
        public string $id,
        public string $name,
        public int $likes,
        public string $photoPath,
        public string $audioId,
        public string $musicPath,
        public bool $isFavourite,
        public string $albumId,
        public string $albumName,
        public string $artistId,
        public string $artistName,
    ) {}
}
