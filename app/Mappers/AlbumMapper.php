<?php

namespace App\Mappers;

use App\DataTransferObjects\AlbumDto;
use App\Exceptions\DataAccessExceptions\DataAccessException;
use App\Facades\AuthFacade;
use App\Models\Album;
use App\Repository\Interfaces\IArtistRepository;
use App\Repository\Interfaces\IFavouritesRepository;
use App\Repository\Interfaces\IGenreRepository;
use App\Repository\Interfaces\ISongRepository;


class AlbumMapper
{
    public function __construct(
        private readonly IGenreRepository $genreRepository,
        private readonly IArtistRepository $artistRepository,
        private readonly IFavouritesRepository $favouritesRepository,
    ) {}

    /**
     * @param Album[] $albums
     * @return AlbumDto[]
     * @throws DataAccessException
     */
    public function mapMultipleAlbums(array $albums): array
    {
        $authUserId = AuthFacade::getUserId();
        $favouriteAlbumsIdsGroup = $this->favouritesRepository->getFavouriteAlbumsIds($authUserId);
        $albumsDtoGroup = [];

        foreach($albums as $album) {
            $albumDto = $this->map($album);
            $albumDto->isFavourite = in_array($albumDto->id, $favouriteAlbumsIdsGroup);
            $albumsDtoGroup[] = $albumDto;
        }

        return $albumsDtoGroup;
    }

    /**
     * @param Album $album map from
     * @return AlbumDto
     * @throws DataAccessException
     */
    public function mapSingleAlbum(Album $album): AlbumDto
    {
        $albumDto = $this->map($album);
        $authUserId = AuthFacade::getUserId();
        $albumDto->isFavourite = $this->favouritesRepository->checkAlbumIsFavourite($authUserId, $albumDto->id);

        return $albumDto;
    }

    /**
     * @throws DataAccessException
     */
    private function map(Album $album): AlbumDto
    {
        $albumDto = new AlbumDto();
        $albumDto->id = $album->id;
        $albumDto->name = $album->name;
        $albumDto->likes = $album->likes;
        $albumDto->photoPath = $album->photo_path;
        $albumDto->artistId = $album->artist_id;
        $albumDto->artistName = $this->artistRepository->getById($albumDto->artistId)->name;
        $albumDto->genreId = $album->genre_id;
        $albumDto->genreName = $this->genreRepository->getById($albumDto->genreId)->name;

        return $albumDto;
    }
}
