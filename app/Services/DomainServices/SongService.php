<?php

namespace App\Services\DomainServices;

use App\DataAccessLayer\Repository\Interfaces\IAlbumRepository;
use App\DataAccessLayer\Repository\Interfaces\IArtistRepository;
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
        $artistId = $this->artistRepository->getByUserId($userId)->_id;

        $musicPath = $this->audioStorageService->saveAudio($album->cdnFolderId, $musicFile);

        return $this->songRepository->create(
            $name,
            $album->photoPath,
            $musicPath,
            $albumId,
            $artistId
        );
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
        $this->audioStorageService->deleteAudio($song->musicPath);
        $this->songRepository->delete($songId);
    }
}
