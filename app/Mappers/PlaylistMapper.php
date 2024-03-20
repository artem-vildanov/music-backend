<?php

namespace App\Mappers;

use App\DataTransferObjects\PlaylistDto;
use App\Models\Playlist;
use App\Repository\Interfaces\IPlaylistSongsRepository;
use App\Repository\Interfaces\ISongRepository;

class PlaylistMapper
{
    public function __construct(

    ) {}

    /**
     * @param Playlist[] $playlists
     * @return PlaylistDto[]
     */
    public function mapMultiplePlaylists(array $playlists): array
    {
        $playlistDtoCollection = [];

        foreach ($playlists as $playlist) {
            $playlistDtoCollection[] = $this->mapSinglePlaylist($playlist);
        }

        return $playlistDtoCollection;
    }

    public function mapSinglePlaylist(Playlist $playlist): PlaylistDto
    {
        $playlistDto = new PlaylistDto();
        $playlistDto->id = $playlist->id;
        $playlistDto->name = $playlist->name;
        $playlistDto->photoPath = $playlist->photo_path;
        $playlistDto->userId = $playlist->user_id;

        return $playlistDto;
    }
}
