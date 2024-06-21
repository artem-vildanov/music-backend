<?php

namespace App\DtoLayer\BigDtoModels;

class GenreBigDto
{
    public function __construct(
        public string $id,
        public string $name,
        public int $likes,
        public bool $isFavourite,
    ) {}
}
