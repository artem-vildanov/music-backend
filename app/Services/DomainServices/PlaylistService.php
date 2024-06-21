<?php

namespace App\Services\DomainServices;

use App\DataAccessLayer\DbModels\Playlist;
use App\DataAccessLayer\Repository\Interfaces\IPlaylistRepository;
use App\Exceptions\DataAccessExceptions\DataAccessException;
use App\Exceptions\MinioException;
use App\Services\FilesStorageServices\PhotoStorageService;
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


        // $newPhotoPath = $this->updatePhoto($playlist->photo_path, $playlistPhoto);

        $newPhotoPath = '';

        if ($playlist->photo_status === "unset") {
            $newPhotoPath = $this->photoStorageService->savePhoto($playlistPhoto, Playlist::getModelName());
        }

        elseif ($playlist->photo_status === "set") {
            $newPhotoPath = $this->photoStorageService
                ->updatePhoto(
                    $playlist->photo_path,
                    $playlistPhoto,
                    Playlist::getModelName()
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

        if ($playlist->photo_path) {
            $this->photoStorageService->deletePhoto($playlist->photo_path);
        }

        $this->playlistRepository->delete($playlistId);
    }

}
