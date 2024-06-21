<?php

namespace App\Services\DomainServices;

use App\DataAccessLayer\DbModels\Album;
use App\DataAccessLayer\Repository\Interfaces\IAlbumRepository;
use App\DataAccessLayer\Repository\Interfaces\IArtistRepository;
use App\DataAccessLayer\Repository\Interfaces\IGenreRepository;
use App\DataAccessLayer\Repository\Interfaces\ISongRepository;
use App\Exceptions\DataAccessExceptions\DataAccessException;
use App\Exceptions\MinioException;
use App\Facades\AuthFacade;
use App\Jobs\PublishAlbums;
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
        int $genreId,
        ?string $publishTime
    ): int {
        $this->genreRepository->getById($genreId);

        $photoPath = $this->photoStorageService->savePhoto($albumPhoto, Album::getModelName());

        $authUserId = AuthFacade::getUserId();
        $artist = $this->artistRepository->getByUserId($authUserId);

        $status = 'private';
        if ($publishTime === "null") {
            $publishTime = null;
            $status = 'public';
        }

        $albumId = $this->albumRepository->create(
            $name,
            $photoPath,
            $artist->id,
            $genreId,
            $publishTime,
            $status
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

    public function removePrivateAlbumsFromList(array $albums): array
    {
        $clearedAlbums = [];
        foreach ($albums as $album) {
            if ($this->checkAlbumAccessRights($album)) {
                $clearedAlbums[] = $album;
            }
        }

        return $clearedAlbums;
    }

    /**
     * @return bool returns true if user have rights to get access to album, else returns false
     */
    public function checkAlbumAccessRights(Album $album): bool {
        $authUser = AuthFacade::getAuthInfo();
        return !($album->artist_id !== $authUser->artistId && $album->status === 'private');
    }
}
