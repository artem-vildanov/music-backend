<?php

namespace App\DomainLayer\DomainModels;

class GenreDomain
{
    public function __construct(
        public string $id,
        public string $name,
        public int $likes,
    ) {}
}
