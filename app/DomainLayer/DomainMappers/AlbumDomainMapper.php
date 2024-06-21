<?php

namespace App\DomainLayer\DomainMappers;

use App\DataAccessLayer\Repository\Interfaces\IAlbumRepository;
use App\DataAccessLayer\Repository\Interfaces\IArtistRepository;
use App\DataAccessLayer\Repository\Interfaces\IGenreRepository;
use App\DataAccessLayer\DbModels\Album;
use App\DataAccessLayer\DbModels\BaseModel;
use App\DataAccessLayer\Repository\Interfaces\IUserRepository;
use App\DomainLayer\DomainModels\AlbumDomain;
use App\DomainLayer\DomainModels\ArtistDomain;
use App\DomainLayer\DomainModels\GenreDomain;
use App\Facades\AuthFacade;

class AlbumDomainMapper
{
    private array $favouriteAlbumsIds;
    public function __construct(
        private readonly IArtistRepository $artistRepository,
        private readonly IGenreRepository $genreRepository,
        private readonly IUserRepository $userRepository,
    ) {
        $authUserId = AuthFacade::getUserId();
        $this->favouriteAlbumsIds = $this->userRepository->getById($authUserId)->favouriteAlbumsIds;
    }

    public function mapToDomain(Album $model): AlbumDomain
    {
        return new AlbumDomain(
            id: $model->_id,
            name: $model->name,
            photoPath: $model->photoPath,
            likes: $model->likes,
            isFavourite: $this->checkAlbumIsFavourite($model->_id),
            publishTime: $model->publishTime,
            songsIds: $model->songsIds,
            artistId: $model->artistId,
            artistName: $this->artistRepository->getById($model->artistId)->name,
            genreId: $model->genreId,
            genreName: $this->genreRepository->getById($model->genreId)->name,
        );
    }

    /**
     * @param Album[] $models
     * @return AlbumDomain[]
     */
    public function mapMultipleToDomain(array $models): array
    {
        return array_map(fn (Album $album) => $this->mapToDomain($album), $models);
    }

    private function checkAlbumIsFavourite(string $albumId): bool {
        return in_array($albumId, $this->favouriteAlbumsIds);
    }
}
