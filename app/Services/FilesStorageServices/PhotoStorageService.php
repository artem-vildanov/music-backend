<?php

declare(strict_types=1);

namespace App\Services\FilesStorageServices;

use App\Exceptions\MinioException;
use Aws\S3\S3Client;
use Illuminate\Http\UploadedFile;

class PhotoStorageService
{
    /**
     * @throws MinioException
     */
    public function saveAlbumPhoto(UploadedFile $file): string|null
    {
        $fileName = uniqid(more_entropy: true);
        $filePath = "album/{$fileName}.png";

        return $this->storePhoto($filePath, $file);
    }

    /**
     * @throws MinioException
     */
    public function saveArtistPhoto(UploadedFile $file): string
    {
        $fileName = uniqid(more_entropy: true);
        $filePath = "artist/{$fileName}.png";

        return $this->storePhoto($filePath, $file);
    }

    /**
     * @throws MinioException
     */
    public function savePlaylistPhoto(UploadedFile $file): string
    {
        $fileName = uniqid(more_entropy: true);
        $filePath = "playlist/{$fileName}.png";

        return $this->storePhoto($filePath, $file);
    }

    /**
     * @throws MinioException
     */
    public function updatePhoto(string $filePath, UploadedFile $file): void
    {
        try {
            $this->storePhoto($filePath, $file);
        } catch (MinioException $e) {
            throw MinioException::failedToUpdatePhoto();
        }
    }

    /**
     * @throws MinioException
     */
    public function deletePhoto(string $filePath): void
    {
        $result = $this->getClient()->deleteObject([
            'Bucket' => 'photo',
            'Key' => $filePath,
        ]);

        $statusCode = $result['@metadata']['statusCode'];

        if($statusCode !== 204) {
            throw MinioException::failedToDeletePhoto();
        }
    }

    /**
     * @throws MinioException
     */
    private function storePhoto(string $filePath, UploadedFile $file): string
    {
        $result = $this->getClient()->putObject(
            [
                'Bucket' => 'photo',
                'Key' => $filePath,
                'Body' => $file->getContent(),
            ]
        );

        $statusCode = $result['@metadata']['statusCode'];

        // $this->getClient()->waitUntil('ObjectExists', ['Bucket' => 'photo', 'Key' => $filePath]);

        if ($statusCode !== 200) {
            throw MinioException::failedToSavePhoto();
        }

        return $filePath;
    }

    private function getClient(): S3Client
    {
        return new S3Client(config('aws'));
    }

}
