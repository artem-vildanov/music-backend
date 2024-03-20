<?php

namespace App\DataTransferObjects;

use App\Models\Artist;
use App\Models\Song;

class SongDto
{
    public int $id;
    public string $name;
    public string $photoPath;
    public string $musicPath;
    public int $likes;
    public int $albumId;
    public string $albumName;
    public int $artistId;
    public string $artistName;
    public bool $isFavourite;


    /**
     * @var PlaylistDto[]
     */
    public array $containedInPlaylists;
}
