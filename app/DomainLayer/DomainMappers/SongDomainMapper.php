<?php

namespace App\DomainLayer\DomainMappers;

use App\DataAccessLayer\DbModels\BaseModel;
use App\DataAccessLayer\DbModels\Playlist;
use App\DataAccessLayer\DbModels\Song;
use App\DataAccessLayer\Repository\Interfaces\IAlbumRepository;
use App\DataAccessLayer\Repository\Interfaces\IArtistRepository;
use App\DataAccessLayer\Repository\Interfaces\IUserRepository;
use App\DomainLayer\DomainModels\DomainModel;
use App\DomainLayer\DomainModels\SongDomain;
use App\Exceptions\DataAccessExceptions\DataAccessException;
use App\Facades\AuthFacade;

class SongDomainMapper
{
    /** @var string[] */
    private ?array $favouriteSongsIds = null;
    public function __construct(
        private readonly IArtistRepository $artistRepository,
        private readonly IAlbumRepository $albumRepository,
        private readonly IUserRepository $userRepository,
    ) {}

    /**
     * @param Song[] $models
     * @return SongDomain[]
     */
    public function mapMultipleToDomain(array $models): array
    {
        return array_map(fn (Song $song) => $this->mapToDomain($song), $models);
    }

    /**
     * @throws DataAccessException
     */
    public function mapToDomain(Song $model): SongDomain
    {
        $this->favouriteSongsIds ?? $this->favouriteSongsIds = $this->getFavouriteSongsIds();
        return new SongDomain(
            id: $model->_id,
            name: $model->name,
            likes: $model->likes,
            photoPath: $model->photoPath,
            musicPath: $model->musicPath,
            isFavourite: $this->checkSongIsFavourite($model->_id),
            albumId: $model->albumId,
            albumName: $this->albumRepository->getById($model->albumId)->name,
            artistId: $model->artistId,
            artistName: $this->artistRepository->getById($model->artistId)->name,
        );
    }

    private function checkSongIsFavourite(string $songId): bool
    {
        return in_array($songId, $this->favouriteSongsIds);
    }

    /**
     * @return string[]
     * @throws DataAccessException
     */
    private function getFavouriteSongsIds(): array
    {
        $authUserId = AuthFacade::getUserId();
        return $this->userRepository->getById($authUserId)->favouriteSongsIds ?? [];
    }
}
