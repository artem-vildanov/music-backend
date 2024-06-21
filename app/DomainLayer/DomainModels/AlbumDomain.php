<?php

namespace App\DomainLayer\DomainModels;

class AlbumDomain
{
    public function  __construct(
        public string $id,
        public string $name,
        public string $photoPath,
        public int $likes,
        public bool $isFavourite,
        public ?string $publishTime,
        /** @var string[] */
        public array $songsIds,
        public string $artistId,
        public string $artistName,
        public string $genreId,
        public string $genreName,
    ) {}
}
