<?php

namespace App\Http\Controllers;

use App\DataAccessLayer\Repository\Interfaces\IAlbumRepository;
use App\DataAccessLayer\Repository\Interfaces\IGenreRepository;
use App\DtoLayer\DtoMappers\AlbumDtoMapper;
use App\DtoLayer\DtoMappers\GenreDtoMapper;
use App\Exceptions\DataAccessExceptions\DataAccessException;
use App\Services\DomainServices\AlbumService;
use Illuminate\Http\JsonResponse;

class GenreController extends Controller
{
    public function __construct(
        private readonly IGenreRepository $genreRepository,
        private readonly IAlbumRepository $albumRepository,
        private readonly GenreDtoMapper   $genreMapper,
        private readonly AlbumDtoMapper   $albumMapper,
        private readonly AlbumService     $albumService
    ) {}

    public function show(int $genreId): JsonResponse
    {
        $genre = $this->genreRepository->getById($genreId);
        $genreDto = $this->genreMapper->mapSingleGenre($genre);

        return response()->json($genreDto);
    }

    public function showAll(): JsonResponse
    {
        $genresModelsGroup = $this->genreRepository->getAll();
        $genresDtoGroup = $this->genreMapper->mapMultipleGenres($genresModelsGroup);

        return response()->json($genresDtoGroup);
    }

    /**
     * @throws DataAccessException
     */
    public function albumsWithGenre(int $genreId): JsonResponse
    {
        $albumsModelsGroup = $this->albumRepository->getAllByGenre($genreId);
        $albumsDtoGroup = $this->albumMapper->mapMultipleAlbums($albumsModelsGroup);

        return response()->json($albumsDtoGroup);
    }
}
