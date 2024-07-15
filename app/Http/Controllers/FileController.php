<?php

namespace App\Http\Controllers;

use App\DataAccessLayer\Repository\Interfaces\IAlbumRepository;
use App\DataAccessLayer\Repository\Interfaces\IFileRepository;
use App\Http\Requests\File\UploadAudioRequest;
use App\Services\FilesStorageServices\AudioStorageService;
use App\Services\FilesStorageServices\PhotoStorageService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FileController extends Controller
{
    public function __construct(
        private readonly PhotoStorageService $photoStorageService,
        private readonly AudioStorageService $audioStorageService,
        private readonly IFileRepository $fileRepository,
        private readonly IAlbumRepository $albumRepository,
    ) {}

    public function uploadSongAudio(string $albumId, UploadAudioRequest $request): JsonResponse {
        $audio = $request->body();
        $albumFolderId = $this->albumRepository->getById($albumId)->cdnFolderId;
        $filePath = $this->audioStorageService->saveAudio($albumFolderId, $audio);
        $fileId = $this->fileRepository->createFile($filePath);
        return response()->json($fileId);
    }
}
