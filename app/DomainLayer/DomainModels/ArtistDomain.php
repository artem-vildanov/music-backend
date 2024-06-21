<?php

namespace App\DomainLayer\DomainModels;

class ArtistDomain
{
    public function __construct(
        public string $id,
        public string $name,
        public int $likes,
        public string $photoPath,
        public string $userId,
        /** @var string[] */
        public array $albumsIds
    ) {}
}
