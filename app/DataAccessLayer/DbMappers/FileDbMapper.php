<?php

namespace App\DataAccessLayer\DbMappers;

use App\DataAccessLayer\DbModels\File;

class FileDbMapper
{
    /**
    * @param File[] $files
    * @return File[]
    */
    public function mapMultipleDbFiles(array $files): array {
        return array_map(fn ($file) => $this->mapDbFile(file), $files);
    }

    public function mapDbFile(File $file): File {
        $this->mapId($file);
        return $file;
    }

    private function mapId(File $file): void {
        $file->id = $file->_id;
        $file->_id = null;
    }
}
