<?php

namespace App\Services\DomainServices;

use App\Exceptions\DataAccessExceptions\DataAccessException;
use App\Exceptions\MinioException;
use App\Facades\AuthFacade;
use App\Models\Album;
use App\Repository\Interfaces\IAlbumRepository;
use App\Repository\Interfaces\IArtistRepository;
use App\Repository\Interfaces\IGenreRepository;
use App\Repository\Interfaces\ISongRepository;
use App\Services\CacheServices\AlbumCacheService;
use App\Services\CacheServices\CacheStorageService;
use App\Services\FilesStorageServices\PhotoStorageService;
use Illuminate\Http\UploadedFile;

class AlbumService
{

    public function __construct(
        private readonly IAlbumRepository    $albumRepository,
        private readonly IArtistRepository   $artistRepository,
        private readonly ISongRepository     $songRepository,
        private readonly IGenreRepository    $genreRepository,
        private readonly PhotoStorageService $photoStorageService,
        private readonly SongService         $songService,
    ) {}

    /**
     * @throws MinioException
     * @throws DataAccessException
     */
    public function saveAlbum(
        string $name,
        UploadedFile $albumPhoto,
        int $genreId
    ): int {
        $this->genreRepository->getById($genreId);

        $photoPath = $this->photoStorageService->saveAlbumPhoto($albumPhoto);

        $authUserId = AuthFacade::getUserId();
        $artist = $this->artistRepository->getByUserId($authUserId);

        return $this->albumRepository->create($name, $photoPath, $artist->id, $genreId);
    }

    /**
     * @throws DataAccessException
     * @throws MinioException
     */
    public function updateAlbum(
        int $albumId,
        ?string $name,
        ?UploadedFile $photoFile,
        ?string $status,
        ?int $genreId
    ): void {

        $album = $this->albumRepository->getById($albumId);
        $updatedAlbum = $album;

        if ($genreId) {
            $this->genreRepository->getById($genreId);
            $updatedAlbum->genre_id = $genreId;
        }

        if ($name) {
            $updatedAlbum->name = $name;
        }

        if ($photoFile) {
            $this->photoStorageService->updatePhoto($album->photo_path, $photoFile);
        }

        if ($status) {
            $updatedAlbum->status = $status;
        }

        $this->albumRepository->update(
            $albumId,
            $updatedAlbum->name,
            $updatedAlbum->photo_path,
            $updatedAlbum->genre_id
        );
    }

    /**
     * @throws DataAccessException
     * @throws MinioException
     */
    public function deleteAlbum(int $albumId): void
    {
        $album = $this->albumRepository->getById($albumId);

        $this->photoStorageService->deletePhoto($album->photo_path);

        $songs = $this->songRepository->getAllByAlbum($albumId);
        foreach ($songs as $song) {
            $this->songService->deleteSong($song->id);
        }

        $this->albumRepository->delete($albumId);

    }
}
