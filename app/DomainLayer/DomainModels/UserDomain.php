<?php

namespace App\DomainLayer\DomainModels;

use App\DomainLayer\Enums\UserRoles;

class UserDomain
{
    public function __construct(
        public string $id,
        public string $name,
        public string $email,
        public array $favouriteArtistsIds,
        public array $favouriteAlbumsIds,
        public array $favouriteSongsIds,
        public UserRoles $role,
        public ?ArtistDomain $artist,
    ) {}
}
