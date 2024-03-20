<?php

declare(strict_types=1);

namespace App\Services\FilesStorageServices;

use App\Exceptions\MinioException;
use Aws\S3\S3Client;
use Illuminate\Http\UploadedFile;

class AudioStorageService
{
    /**
     * @throws \App\Exceptions\MinioException
     */
    public function saveAudio(string $albumFolderId, UploadedFile $file): string
    {
        $fileName = uniqid(more_entropy: true);
        $filePath = "{$albumFolderId}/{$fileName}.mp3";

        return $this->storeAudio($filePath, $file);
    }

    /**
     * @throws MinioException
     */
    public function updateAudio(string $filePath, UploadedFile $file): void
    {
        try {
            $this->storeAudio($filePath, $file);
        } catch (MinioException $exception) {
            throw MinioException::failedToUpdateAudio();
        }

    }

    /**
     * @throws \App\Exceptions\MinioException
     */
    public function deleteAudio(string $filePath): void
    {
        $result = $this->getClient()->deleteObject([
            'Bucket' => 'audio',
            'Key' => $filePath,
        ]);

        $statusCode = $result['@metadata']['statusCode'];

        if ($statusCode !== 204) {
            throw MinioException::failedToDeleteAudio();
        }
    }

    /**
     * @throws MinioException
     */
    private function storeAudio(string $filePath, UploadedFile $file): string
    {
        $result = $this->getClient()->putObject(
            [
                'Bucket' => 'audio',
                'Key' => $filePath,
                'Body' => $file->getContent(),
            ]
        );

        // $this->getClient()->waitUntil('ObjectExists', ['Bucket' => 'audio', 'Key' => $filePath]);

        $statusCode = $result['@metadata']['statusCode'];

        if ($statusCode !== 200) {
            throw MinioException::failedToSaveAudio();
        }

        return $filePath;
    }


    private function getClient(): S3Client
    {
        return new S3Client(config('aws'));
    }

}
