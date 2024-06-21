<?php

namespace App\DtoLayer\LightDtoModels;

class AlbumLightDto
{
    public string $id;
    public string $name;
    public string $artistId;
    public string $artistName;
    public string $photoPath;
    public bool $isFavourite;
}
