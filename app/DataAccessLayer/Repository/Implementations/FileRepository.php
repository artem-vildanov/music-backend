<?php

namespace App\DataAccessLayer\Repository\Implementations;

use App\DataAccessLayer\DbMappers\FileDbMapper;
use App\DataAccessLayer\DbModels\File;
use App\DataAccessLayer\Repository\Interfaces\IFileRepository;
use App\DomainLayer\Enums\FileStatuses;
use App\Exceptions\DataAccessExceptions\FileException;
use MongoDB\BSON\ObjectId;

class FileRepository implements IFileRepository
{
    public function __construct(private readonly FileDbMapper $fileDbMapper) {}

    public function getFile(string $id): File
    {
        $file = File::where('_id', new ObjectId($id))->first()
            ?? throw FileException::notFound($id);
        return $this->fileDbMapper->mapDbFile($file);
    }

    public function setInuseStatus(string $id): void
    {
        File::where('_id', new ObjectId($id))
            ->update([
                'fileStatus' => FileStatuses::inuse->value
            ]);
    }

    public function createFile(string $filePath): string
    {
        $file = new File();
        $file->filePath = $filePath;
        $file->fileStatus = FileStatuses::unused->value;
        if (!$file->save()) {
            throw FileException::failedToCreate();
        }
        return $file->_id;
    }

    public function deleteFile(string $id): void
    {
        $result = File::destroy($id);
        if (!$result) {
            throw FileException::failedToDelete($id);
        }
    }
}
