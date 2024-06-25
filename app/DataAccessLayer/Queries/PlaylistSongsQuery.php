<?php

namespace App\DataAccessLayer\Queries;

use App\DataAccessLayer\DbMappers\QueryMapper;
use App\DataAccessLayer\DbModels\Song;
use Illuminate\Support\Facades\DB;

class PlaylistSongsQuery
{
    public function __construct(
        private readonly AggregationQueries $aggregationQueries,
        private readonly QueryMapper $queryMapper,
    ) {}

    /**
     * @param string $playlistId
     * @return Song[]
     */
    public function getPlaylistSongs(string $playlistId): array
    {
        $query = $this->aggregationQueries->getPlaylistSongsQuery($playlistId);
        $mongoResponse = DB::connection('mongodb')
            ->collection('playlists')
            ->raw($query);
        return $this->queryMapper->mapMultipleSongs($mongoResponse);
    }
}
