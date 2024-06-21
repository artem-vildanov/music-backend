<?php

namespace App\DomainLayer\DomainModels;

class PlaylistDomain
{
    public function __construct(
        public string $id,
        public string $name,
        public ?string $photoPath,
        public string $userId,
        /** @var string[] */
        public array $songsIds,
    ) {}
}
