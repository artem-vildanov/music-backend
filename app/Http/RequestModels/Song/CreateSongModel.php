<?php

namespace App\Http\RequestModels\Song;

class CreateSongModel
{
    public function __construct(
        public string $name,
        public string $audioId
    ) {}
}
