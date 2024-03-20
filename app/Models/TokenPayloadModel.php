<?php

declare(strict_types=1);

namespace App\Models;

class TokenPayloadModel
{
    public int $id;
    public string $email;
    public string $name;
    public ?int $artistId;
    public int $createdAt;
    public int $refreshableUntil;
    public int $expiredAt;
}
