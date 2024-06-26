<?php

declare(strict_types=1);

namespace App\Services\FilesStorageServices;

use App\Exceptions\MinioException;
use App\Models\BaseModel;
use Aws\S3\S3Client;
use Faker\Provider\Base;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;

class PhotoStorageService
{
    public function savePhoto(UploadedFile $file, string $modelName): string 
    {
        $fileName = uniqid(more_entropy: true);
        $filePath = "{$modelName}/{$fileName}.png";

        return $this->storePhoto($filePath, $file);
    }

    /**
     * @return string new photo path
     */
    public function updatePhoto(string $oldPhotoPath, UploadedFile $newFile, string $modelName): string
    {
        $this->deletePhoto($oldPhotoPath);
        return $this->savePhoto($newFile, $modelName);
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
