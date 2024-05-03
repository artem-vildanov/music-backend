<?php

namespace App\Services\DomainServices;

use App\Exceptions\DataAccessExceptions\DataAccessException;
use App\Exceptions\MinioException;
use App\Facades\AuthFacade;
use App\Repository\Interfaces\IAlbumRepository;
use App\Repository\Interfaces\IArtistRepository;
use App\Repository\Interfaces\IUserRepository;
use App\Services\FilesStorageServices\PhotoStorageService;
use Illuminate\Http\UploadedFile;
use App\Models\Artist;

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
    public function saveArtist(string $name, UploadedFile $photoFile): int|null
    {
        $photoPath = $this->photoStorageService->savePhoto($photoFile, Artist::getModelName());

        if (!$photoPath)
            return null;

        $user = AuthFacade::getAuthInfo();
        $this->userRepository->update($user->id, $user->name, $user->email, 'artist');

        return $this->artistRepository->create($name, $photoPath, $user->id);
    }

    public function updateArtistPhoto(int $artistId, UploadedFile $photoFile): void
    {
        $artist = $this->artistRepository->getById($artistId);
        $newFilePath = $this
            ->photoStorageService
            ->updatePhoto(
                $artist->photo_path,
                $photoFile,
                Artist::getModelName()
            );

        $this->artistRepository->updatePhoto($artistId, $newFilePath);
    }

    /**
     * @throws DataAccessException
     * @throws MinioException
     */
    public function deleteArtist(int $artistId): void
    {
        $artist = $this->artistRepository->getById($artistId);

        $this->photoStorageService->deletePhoto($artist->photo_path);

        $albums = $this->albumRepository->getAllByArtist($artist->id);
        foreach ($albums as $album) {
            $this->albumService->deleteAlbum($album->id);
        }

        $this->artistRepository->delete($artistId);

        $user = AuthFacade::getAuthInfo();
        $this->userRepository->update(
            $user->id,
            $user->name,
            $user->email,
            'base_user'
        );

    }
}
