<?php

namespace App\DataAccessLayer\Repository\Interfaces;

use App\DataAccessLayer\DbModels\File;

interface IFileRepository
{
    public function getFile(string $id): File;
    public function setInuseStatus(string $id): void;
    public function createFile(string $filePath): string;
    public function deleteFile(string $id): void;
}
