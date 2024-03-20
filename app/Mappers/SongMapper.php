<?php

namespace App\Mappers;

use App\DataTransferObjects\SongDto;
use App\Exceptions\DataAccessExceptions\DataAccessException;
use App\Facades\AuthFacade;
use App\Models\Song;
use App\Repository\Interfaces\IAlbumRepository;
use App\Repository\Interfaces\IArtistRepository;
use App\Repository\Interfaces\IFavouritesRepository;
use App\Repository\Interfaces\IPlaylistRepository;
use App\Repository\Interfaces\IPlaylistSongsRepository;
use App\Services\CacheServices\AlbumCacheService;
use App\Services\DomainServices\AlbumService;

class SongMapper
{
    public function __construct(
        private readonly IArtistRepository        $artistRepository,
        private readonly IFavouritesRepository    $favouritesRepository,
        private readonly IPlaylistRepository      $playlistRepository,
        private readonly IPlaylistSongsRepository $playlistSongsRepository,
        private readonly PlaylistMapper $playlistMapper,
        private readonly IAlbumRepository $albumRepository
    ) {}

    /**
     * @param Song[] $songs
     * @return SongDto[]
     * @throws DataAccessException
     */
    public function mapMultipleSongs(array $songs): array
    {

        /** @var SongDto[] $songDtoCollection */
        $songsDtoGroup = [];

        foreach ($songs as $song) {
            $songsDtoGroup[] = $this->map($song);
        }

        return $songsDtoGroup;
    }


    /**
     * @param Song $song
     * @return SongDto
     * @throws DataAccessException
     */
    public function mapSingleSong(Song $song): SongDto
    {
        return $this->map($song);
    }

    /**
     * @throws DataAccessException
     */
    private function map(Song $song): SongDto
    {
        $songDto = new SongDto();
        $songDto->id = $song->id;
        $songDto->name = $song->name;
        $songDto->likes = $song->likes;
        $songDto->photoPath = $song->photo_path;
        $songDto->musicPath = $song->music_path;
        $songDto->albumId = $song->album_id;
        $songDto->albumName = $this->albumRepository->getById($song->id)->name;
        $songDto->artistId = $song->artist_id;
        $songDto->artistName = $this->artistRepository->getById($songDto->artistId)->name;
        $songDto->isFavourite = $this->checkSongIsFavourite($songDto->id);
        $songDto->containedInPlaylists = $this->getPlaylistsWithSong($songDto->id);

        return $songDto;
    }

    private function checkSongIsFavourite(int $songId): bool
    {
        $userId = AuthFacade::getUserId();
        return $this->favouritesRepository->checkSongIsFavourite($userId, $songId);
    }

    private function getPlaylistsWithSong(int $songId): array
    {
        $userId = AuthFacade::getUserId();
        $playlistsIdsGroup = $this->playlistSongsRepository->getUserPlaylistsIdsWithSong($songId, $userId);
        $playlistsModelsGroup = $this->playlistRepository->getMultipleByIds($playlistsIdsGroup);
        return $this->playlistMapper->mapMultiplePlaylists($playlistsModelsGroup);
    }

}
