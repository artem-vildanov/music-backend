<?php

declare(strict_types = 1);

namespace App\DomainLayer\DomainMappers;

use App\DataAccessLayer\DbModels\Album;
use App\DataAccessLayer\Repository\Interfaces\IArtistRepository;
use App\DataAccessLayer\Repository\Interfaces\IUserRepository;
use App\DomainLayer\DomainModels\AlbumDomain;
use App\DomainLayer\Enums\Genres;
use App\Facades\AuthFacade;

class AlbumDomainMapper
{
    private ?array $favouriteAlbumsIds = null;
    public function __construct(
        private readonly IArtistRepository $artistRepository,
        private readonly IUserRepository $userRepository,
    ) {}

    public function mapToDomain(Album $model): AlbumDomain
    {
        $favouriteAlbumsIds ?? $this->favouriteAlbumsIds = $this->getFavouriteAlbumsIds();
        return new AlbumDomain(
            id: $model->id,
            name: $model->name,
            photoPath: $model->photoPath,
            likes: $model->likes,
            isFavourite: $this->checkAlbumIsFavourite($model->id),
            publishTime: $model->publishTime,
            artistId: $model->artistId,
            artistName: $this->artistRepository->getById($model->artistId)->name,
            genre: Genres::from($model->genre),
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

    private function getFavouriteAlbumsIds(): array
    {
        $authUserId = AuthFacade::getUserId();
        return $this->userRepository->getById($authUserId)->favouriteAlbumsIds ?? [];
    }
}
