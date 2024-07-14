<?php

namespace App\Services\DomainServices;

use App\DataAccessLayer\Repository\Interfaces\IAlbumRepository;
use App\DataAccessLayer\Repository\Interfaces\IArtistRepository;
use App\DataAccessLayer\Repository\Interfaces\IFileRepository;
use App\DataAccessLayer\Repository\Interfaces\IPlaylistRepository;
use App\DataAccessLayer\Repository\Interfaces\ISongRepository;
use App\DataAccessLayer\Repository\Interfaces\IUserRepository;
use App\Exceptions\DataAccessExceptions\DataAccessException;
use App\Exceptions\MinioException;
use App\Facades\AuthFacade;
use App\Services\FilesStorageServices\AudioStorageService;
use Illuminate\Http\UploadedFile;

class SongService
{
    public function __construct(
        private readonly AudioStorageService $audioStorageService,
        private readonly ISongRepository     $songRepository,
        private readonly IAlbumRepository $albumRepository,
        private readonly IArtistRepository $artistRepository,
        private readonly IPlaylistRepository $playlistRepository,
        private readonly IUserRepository     $userRepository,
        private readonly IFileRepository $fileRepository,
    ) {
    }

    /**
     * @throws DataAccessException
     * @throws MinioException
     */
    public function saveSong(string $name, UploadedFile $musicFile, string $albumId): string
    {
        $album = $this->albumRepository->getById($albumId);

        $userId = AuthFacade::getUserId();
        $artistId = $this->artistRepository->getByUserId($userId)->id;

        $filePath = $this->audioStorageService->saveAudio($album->cdnFolderId, $musicFile);
        $audioId = $this->fileRepository->createFile($filePath);

        $songId = $this->songRepository->create(
            $name,
            $album->photoPath,
            $audioId,
            $albumId,
            $artistId
        );

        $this->fileRepository->setInuseStatus($audioId);
        return $songId;
    }

    /**
     * @throws DataAccessException
     * @throws MinioException
     */
    public function updateSongAudio(string $songId, UploadedFile $audioFile): void
    {
        $song = $this->songRepository->getById($songId);
        $album = $this->albumRepository->getById($song->albumId);

        $newFilePath = $this
            ->audioStorageService
            ->updateAudio(
                $song->musicPath,
                $album->cdnFolderId,
                $audioFile
            );
        $this->songRepository->updateAudio($songId, $newFilePath);
    }

    /**
     * @throws DataAccessException
     * @throws MinioException
     */
    public function deleteSong(string $songId): void
    {
        $song = $this->songRepository->getById($songId);

        $this->playlistRepository->removeSongFromAllPlaylists($songId);
        $this->userRepository->removeSongFromAllUsers($songId);
        $filePath = $this->fileRepository->getFile($song->audioId);
        $this->fileRepository->deleteFile($song->audioId);
        $this->audioStorageService->deleteAudio($filePath);
        $this->songRepository->delete($songId);
    }
}
