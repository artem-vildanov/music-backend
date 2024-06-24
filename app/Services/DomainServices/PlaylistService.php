<?php

namespace App\Services\DomainServices;

use App\DataAccessLayer\DbModels\Playlist;
use App\DataAccessLayer\Repository\Interfaces\IPlaylistRepository;
use App\Exceptions\DataAccessExceptions\DataAccessException;
use App\Exceptions\MinioException;
use App\Services\FilesStorageServices\PhotoStorageService;
use App\Utils\Enums\ModelNames;
use App\Utils\Enums\PhotoStatuses;
use Illuminate\Http\UploadedFile;

class PlaylistService
{
    public function __construct(
        private readonly IPlaylistRepository $playlistRepository,
        private readonly PhotoStorageService $photoStorageService,
    ) {}

    /**
     * @throws MinioException
     * @throws DataAccessException
     */
    public function updatePlaylistPhoto(int $playlistId, UploadedFile $playlistPhoto): void
    {
        $playlist = $this->playlistRepository->getById($playlistId);
        $newPhotoPath = '';

        if ($playlist->photoStatus === PhotoStatuses::unset->value) {
            $newPhotoPath = $this->photoStorageService->savePhoto($playlistPhoto, ModelNames::Playlist);
        }

        elseif ($playlist->photoStatus === PhotoStatuses::set->value) {
            $newPhotoPath = $this
                ->photoStorageService
                ->updatePhoto(
                    $playlist->photoPath,
                    $playlistPhoto,
                    ModelNames::Playlist
                );
        }

        $this->playlistRepository->updatePhoto(
            $playlistId,
            $newPhotoPath
        );
    }

    /**
     * @throws DataAccessException
     * @throws MinioException
     */
    public function deletePlaylist(int $playlistId): void
    {
        $playlist = $this->playlistRepository->getById($playlistId);

        if ($playlist->photoPath) {
            $this->photoStorageService->deletePhoto($playlist->photoPath);
        }

        $this->playlistRepository->delete($playlistId);
    }


}
