<?php

declare(strict_types=1);

namespace App\DataAccessLayer\DbModels;

class TokenPayloadModel
{
    public function __construct(
        public string $id,
        public string $name,
        public string $email,
        public ?string $artistId,
        public int $createdAt,
        public int $refreshableUntil,
        public int $expiredAt,
    ) {}
}
