<?php

declare(strict_types = 1);

namespace App\DataAccessLayer\Queries;

use App\DataAccessLayer\DbMappers\QueryMapper;
use App\DomainLayer\Enums\ModelNames;
use Illuminate\Support\Facades\DB;
use MongoDB\Driver\Cursor;

class FavouriteQuery
{
    public function __construct(
        private readonly QueryMapper $eloquentMapper,
        private readonly AggregationQueries $aggregationQueries,
    ) {}

    public function getFavouriteAlbums(string $userId): array
    {
        $mongoResponse = $this->getFavourite(ModelNames::Album, $userId);
        return $this->eloquentMapper->mapMultipleAlbums($mongoResponse);
    }

    public function getFavouriteArtists(string $userId): array
    {
        $mongoResponse = $this->getFavourite(ModelNames::Artist, $userId);
        return $this->eloquentMapper->mapMultipleArtists($mongoResponse);
    }

    public function getFavouriteSongs(string $userId): array
    {
        $mongoResponse = $this->getFavourite(ModelNames::Song, $userId);
        return $this->eloquentMapper->mapMultipleSongs($mongoResponse);
    }

    /**
     * get all favourite of chosen type
     * @param ModelNames $modelName type to choose
     * @param string $userId user, whose favourites we want to get
     * @return Cursor raw mongodb response
     */
    private function getFavourite(ModelNames $modelName, string $userId): Cursor
    {
        $query = $this->aggregationQueries->getFavouritesQuery($userId, $modelName);
        return DB::connection('mongodb')
            ->collection('users')
            ->raw($query);
    }
}
