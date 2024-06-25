<?php

namespace App\DataAccessLayer\Queries;

use App\DomainLayer\Enums\ModelNames;
use MongoDB\BSON\ObjectId;

class AggregationQueries
{
    public function getFavouritesQuery(string $userId, ModelNames $modelName): callable
    {
        $from = "{$modelName->value}s";
        $localField = 'favourite' . ucfirst($from) . 'Ids';
        return $this->getQuery($userId, $from, $localField);
    }

    public function getPlaylistSongsQuery(string $playlistId): callable
    {
        $from = 'songs';
        $localField = 'songsIds';
        return $this->getQuery($playlistId, $from, $localField);
    }

    private function getQuery(string $matchId, string $from, string $localField): callable
    {
        $aggregationQuery = $this->getAggregationQuery(
            $matchId,
            $from,
            $localField,
        );

        return function($collection) use ($aggregationQuery) {
            return $collection->aggregate($aggregationQuery);
        };
    }

    private function getAggregationQuery(string $matchId, string $from, string $localField): array
    {
        return [
            [
                '$match' => [ '_id' => new ObjectId($matchId) ]
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
        ];
    }
}
