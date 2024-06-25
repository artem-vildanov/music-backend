<?php

namespace App\DomainLayer\Enums;

enum ModelNames: string {
    case Album = 'album';
    case Artist = 'artist';
    case Playlist = 'playlist';
    case Song = 'song';
}
