<?php

declare(strict_types = 1);

namespace App\Services\DomainServices;

use App\DataAccessLayer\DbModels\Album;
use App\DataAccessLayer\Repository\Interfaces\IAlbumRepository;
use App\DataAccessLayer\Repository\Interfaces\IArtistRepository;
use App\DataAccessLayer\Repository\Interfaces\ISongRepository;
use App\DataAccessLayer\Repository\Interfaces\IUserRepository;
use App\Exceptions\DataAccessExceptions\DataAccessException;
use App\Exceptions\GenreException;
use App\Exceptions\MinioException;
use App\Facades\AuthFacade;
use App\Services\FilesStorageServices\PhotoStorageService;
use App\Utils\Enums\Genres;
use App\Utils\Enums\ModelNames;
use Illuminate\Http\UploadedFile;

class AlbumService
{
    public function __construct(
        private readonly IUserRepository $userRepository,
        private readonly IAlbumRepository    $albumRepository,
        private readonly IArtistRepository   $artistRepository,
        private readonly ISongRepository     $songRepository,
        private readonly PhotoStorageService $photoStorageService,
        private readonly SongService         $songService,
    ) {}

    /**
     * @throws DataAccessException
     * @throws GenreException
     */
    public function saveAlbum(
        string $name,
        UploadedFile $albumPhoto,
        string $genreName,
        ?string $publishTime
    ): string {
        $this->checkGenreExists($genreName);

        $photoPath = $this->photoStorageService->savePhoto($albumPhoto, ModelNames::Album);

        $authUserId = AuthFacade::getUserId();
        $artist = $this->artistRepository->getByUserId($authUserId);

        if ($publishTime === "null") {
            $publishTime = null;
        }

        return $this->albumRepository->create(
            $name,
            $photoPath,
            $artist->_id,
            $genreName,
            $publishTime,
        );
    }

    public function checkGenreExists(string $inputGenre): void
    {
        $result = Genres::tryFrom($inputGenre);
        if (!$result) {
            throw GenreException::notFound($inputGenre);
        }
    }

    public function updateAlbumPhoto(string $albumId, UploadedFile $file): void
    {
        $album = $this->albumRepository->getById($albumId);
        $newFilePath = $this
            ->photoStorageService
            ->updatePhoto(
                $album->photoPath,
                $file,
                ModelNames::Album
            );

        $this->albumRepository->updatePhoto($albumId, $newFilePath);
        $this->updateAlbumSongsPhoto($albumId, $newFilePath);
    }

    /**
     * update photoPath for album songs
     */
    private function updateAlbumSongsPhoto(string $albumId, string $filePath): void
    {
        $albumSongs = $this->songRepository->getAllByAlbum($albumId);
        foreach ($albumSongs as $song) {
            $this->songRepository->updatePhoto($song->_id, $filePath);
        }
    }

    /**
     * @throws DataAccessException
     * @throws MinioException
     */
    public function deleteAlbum(string $albumId): void
    {
        $album = $this->albumRepository->getById($albumId);

        $this->userRepository->removeAlbumFromAllUsers($albumId);
        $this->photoStorageService->deletePhoto($album->photoPath);
        $this->deleteAlbumSongs($albumId);
        $this->albumRepository->delete($albumId);
    }

    private function deleteAlbumSongs(string $albumId): void
    {
        $songs = $this->songRepository->getAllByAlbum($albumId);
        foreach ($songs as $song) {
            $this->songService->deleteSong($song->_id);
        }
    }

    public function publishAllReadyAlbums(): void
    {
        $albums = $this->albumRepository->getAllReadyToPublish();
        foreach ($albums as $album) {
            $this->albumRepository->makePublic($album->_id);
        }
    }

    /**
     * @param Album[] $albums альбомы, из которых надо убрать недоступные
     * @return Album[] только доступные альбомы
     */
    public function removePrivateAlbumsFromList(array $albums): array
    {
        $authUserArtistId = AuthFacade::getAuthInfo()->artistId;
        return array_filter(
            $albums,
            fn ($album) => $this->checkAlbumAccessRights($album, $authUserArtistId)
        );
    }

    /**
     * @return bool returns true if user have rights to get access to album, else returns false
     */
    private function checkAlbumAccessRights(Album $album, string $authUserArtistId): bool {
        return !(
            $album->artistId !== $authUserArtistId
            && $album->publishTime !== null
        );
    }
}
