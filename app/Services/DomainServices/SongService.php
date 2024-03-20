<?php

namespace App\Services\DomainServices;

use App\Exceptions\DataAccessExceptions\DataAccessException;
use App\Exceptions\MinioException;
use App\Repository\Interfaces\IAlbumRepository;
use App\Repository\Interfaces\ISongRepository;
use App\Services\CacheServices\AlbumCacheService;
use App\Services\FilesStorageServices\AudioStorageService;
use Illuminate\Http\UploadedFile;

class SongService
{
    public function __construct(
        private readonly AudioStorageService $storageService,
        private readonly ISongRepository     $songRepository,
        private readonly IAlbumRepository $albumRepository,
    ) {
    }

    /**
     * @throws DataAccessException
     * @throws MinioException
     */
    public function saveSong(string $name, UploadedFile $musicFile, int $albumId): int
    {
        $album = $this->albumRepository->getById($albumId);

        $musicPath = $this->storageService->saveAudio($album->cdn_folder_id, $musicFile);

        return $this->songRepository->create($name, $album->photo_path, $musicPath, $albumId);
    }

    /**
     * @throws DataAccessException
     * @throws MinioException
     */
    public function updateSong(int $songId, ?string $name, ?UploadedFile $musicFile): void
    {
        $song = $this->songRepository->getById($songId);
        $updatedSong = $song;

        if ($name) {
            $updatedSong->name = $name;
        }

        if ($musicFile) {
            $this->storageService->updateAudio($song->music_path, $musicFile);
        }

        $this->songRepository->update(
            $updatedSong->id,
            $updatedSong->name,
        );
    }

    /**
     * @throws DataAccessException
     * @throws MinioException
     */
    public function deleteSong(int $songId): void
    {
        $song = $this->songRepository->getById($songId);

        $this->storageService->deleteAudio($song->music_path);
        $this->songRepository->delete($songId);
    }
}
