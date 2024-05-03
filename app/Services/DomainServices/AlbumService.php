<?php

namespace App\Services\DomainServices;

use App\Exceptions\DataAccessExceptions\AlbumException;
use App\Exceptions\DataAccessExceptions\DataAccessException;
use App\Exceptions\MinioException;
use App\Facades\AuthFacade;
use App\Jobs\PublishAlbums;
use App\Models\Album;
use App\Repository\Interfaces\IAlbumRepository;
use App\Repository\Interfaces\IArtistRepository;
use App\Repository\Interfaces\IGenreRepository;
use App\Repository\Interfaces\ISongRepository;
use App\Services\CacheServices\AlbumCacheService;
use App\Services\CacheServices\CacheStorageService;
use App\Services\FilesStorageServices\PhotoStorageService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Queue;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;

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
        int $genreId,
        ?string $publishTime
    ): int {
        $this->genreRepository->getById($genreId);

        $photoPath = $this->photoStorageService->savePhoto($albumPhoto, Album::getModelName());

        $authUserId = AuthFacade::getUserId();
        $artist = $this->artistRepository->getByUserId($authUserId);

        if (!$publishTime) {
            $publishTime = now();
        }

        $albumId = $this->albumRepository->create(
            $name,
            $photoPath,
            $artist->id, 
            $genreId,
            $publishTime
        );
        
        return $albumId;
    }

    public function updateAlbumPhoto(int $albumId, UploadedFile $file): void
    {
        $album = $this->albumRepository->getById($albumId);
        $newFilePath = $this
            ->photoStorageService
            ->updatePhoto(
                $album->photo_path,
                $file,
                Album::getModelName()
            );

        $this->albumRepository->updatePhoto($albumId, $newFilePath);

        /**
         * update photoPath for album songs
         */
        $albumSongs = $this->songRepository->getAllByAlbum($albumId);
        foreach ($albumSongs as $song) {
            $this->songRepository->updatePhoto($song->id, $newFilePath);
        }
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

    public function publishAllReadyAlbums(): void 
    {
        $albums = $this->albumRepository->getAllReadyToPublish();
        foreach ($albums as $album) {
            $this->albumRepository->makePublic($album->id);
        }
    }
}
