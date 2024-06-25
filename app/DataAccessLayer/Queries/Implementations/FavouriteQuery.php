<?php

declare(strict_types = 1);

namespace App\DataAccessLayer\Queries\Implementations;

use App\DataAccessLayer\DbMappers\QueryMapper;
use App\DataAccessLayer\Queries\Interfaces\IFavouriteQuery;
use App\DomainLayer\Enums\ModelNames;
use Illuminate\Support\Facades\DB;
use MongoDB\BSON\ObjectId;
use MongoDB\Driver\Cursor;

class FavouriteQuery implements IFavouriteQuery
{
    public function __construct(
        private readonly QueryMapper $eloquentMapper,
    ) {}

    public function getFavouriteAlbums(string $userId): array
    {
        $mongoResponse = $this->getFavourite(ModelNames::Album, $userId);
        return $this->eloquentMapper->mapFavouriteAlbums($mongoResponse);
    }

    public function getFavouriteArtists(string $userId): array
    {
        $mongoResponse = $this->getFavourite(ModelNames::Artist, $userId);
        return $this->eloquentMapper->mapFavouriteArtists($mongoResponse);
    }

    public function getFavouriteSongs(string $userId): array
    {
        $mongoResponse = $this->getFavourite(ModelNames::Song, $userId);
        return $this->eloquentMapper->mapFavouriteSongs($mongoResponse);
    }

    /**
     * get all favourite of chosen type
     * @param ModelNames $modelName type to choose
     * @param string $userId user, whose favourites we want to get
     * @return Cursor raw mongodb response
     */
    private function getFavourite(ModelNames $modelName, string $userId): Cursor
    {
        $query = $this->getQuery($userId, $modelName);
        return DB::connection('mongodb')
            ->collection('users')
            ->raw($query);
    }

    private function getQuery(string $userId, ModelNames $modelName): callable {
        $from = "{$modelName->value}s";
        $localField = 'favourite' . ucfirst($from) . 'Ids';

        return function($collection) use (
            $userId,
            $from,
            $localField
        ) {
            return $collection->aggregate([
                [
                    '$match' => [ '_id' => new ObjectId($userId) ]
                ],
                [
                    '$lookup' => [
                        'from' => $from,
                        'localField' => $localField,
                        'foreignField' => '_id',
                        'as' => 'result',
                    ]
                ],
                [
                    '$project' => [
                        'result' => 1,
                        '_id' => 0
                    ]
                ]
            ]);
        };
    }
}
