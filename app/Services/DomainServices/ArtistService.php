<?php

declare(strict_types = 1);

namespace App\Services\DomainServices;

use App\DataAccessLayer\Repository\Interfaces\IAlbumRepository;
use App\DataAccessLayer\Repository\Interfaces\IArtistRepository;
use App\DataAccessLayer\Repository\Interfaces\IUserRepository;
use App\DomainLayer\Enums\ModelNames;
use App\DomainLayer\Enums\UserRoles;
use App\Exceptions\DataAccessExceptions\DataAccessException;
use App\Exceptions\MinioException;
use App\Facades\AuthFacade;
use App\Services\FilesStorageServices\PhotoStorageService;
use Illuminate\Http\UploadedFile;

class ArtistService
{
    public function __construct(
        private readonly PhotoStorageService $photoStorageService,
        private readonly IArtistRepository   $artistRepository,
        private readonly IAlbumRepository    $albumRepository,
        private readonly IUserRepository     $userRepository,
        private readonly AlbumService        $albumService
    ) {}

    /**
     * @throws DataAccessException
     * @throws MinioException
     */
    public function saveArtist(string $name, UploadedFile $photoFile): string
    {
        $photoPath = $this
            ->photoStorageService
            ->savePhoto(
                $photoFile,
                ModelNames::Artist
            ) ?? throw MinioException::failedToSavePhoto();

        $this->updateUserRole(UserRoles::ArtistUser);

        return $this->artistRepository->create(
            $name,
            $photoPath,
            AuthFacade::getUserId()
        );
    }

    public function updateArtistPhoto(string $artistId, UploadedFile $photoFile): void
    {
        $artist = $this->artistRepository->getById($artistId);
        $newFilePath = $this
            ->photoStorageService
            ->updatePhoto(
                $artist->photoPath,
                $photoFile,
                ModelNames::Artist
            );

        $this->artistRepository->updatePhoto($artistId, $newFilePath);
    }

    /**
     * @throws DataAccessException
     * @throws MinioException
     */
    public function deleteArtist(string $artistId): void
    {
        $artist = $this->artistRepository->getById($artistId);

        $this->userRepository->removeArtistFromAllUsers($artistId);
        $this->photoStorageService->deletePhoto($artist->photoPath);
        $this->deleteArtistAlbums($artistId);
        $this->artistRepository->delete($artistId);
        $this->updateUserRole(UserRoles::BaseUser);
    }

    private function deleteArtistAlbums(string $artistId): void
    {
        $albums = $this->albumRepository->getAllByArtist($artistId);
        foreach ($albums as $album) {
            $this->albumService->deleteAlbum($album->id);
        }
    }

    private function updateUserRole(UserRoles $userRole): void
    {
        $user = AuthFacade::getAuthInfo();
        $this->userRepository->update(
            $user->id,
            $user->name,
            $user->email,
            $userRole
        );
    }
}
