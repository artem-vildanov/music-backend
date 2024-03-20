<?php

namespace App\Mappers;

use App\DataTransferObjects\GenreDto;
use App\Facades\AuthFacade;
use App\Models\Genre;
use App\Repository\Interfaces\IFavouritesRepository;

class GenreMapper
{
    public function __construct(
        private readonly IFavouritesRepository $favouritesRepository
    ) {}

    public function mapMultipleGenres(array $genres): array
    {
        $authUserId = AuthFacade::getUserId();
        $favouriteGenresIdsGroup = $this->favouritesRepository->getFavouriteGenresIds($authUserId);
        $genresDtoGroup = [];

        foreach($genres as $genre) {
            $genreDto = $this->map($genre);
            $genreDto->isFavourite = in_array($genreDto->id, $favouriteGenresIdsGroup);
            $genresDtoGroup[] = $genreDto;
        }

        return $genresDtoGroup;
    }

    public function mapSingleGenre(Genre $genre): GenreDto
    {
        $genreDto = $this->map($genre);
        $authUserId = AuthFacade::getUserId();
        $genreDto->isFavourite = $this->favouritesRepository->checkGenreIsFavourite($authUserId, $genreDto->id);

        return $genreDto;
    }

    private function map(Genre $genre): GenreDto
    {
        $genreDto = new GenreDto;
        $genreDto->id = $genre->id;
        $genreDto->name = $genre->name;
        $genreDto->likes = $genre->likes;

        return $genreDto;
    }
}
