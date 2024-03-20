<?php

namespace App\Http\Middleware;

use App\Exceptions\DataAccessExceptions\DataAccessException;
use App\Repository\Interfaces\ISongRepository;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckSongExists
{
    public function __construct(
        private readonly ISongRepository $songRepository
    ) {}

    /**
     * @throws DataAccessException
     */
    public function handle(Request $request, Closure $next): Response
    {
        $songId = (int)$request->route('songId');
        $albumId = (int)$request->route('albumId');

        $song = $this->songRepository->getById($songId);

        if ($song->album_id !== $albumId) {
            return response()->json('', 404);
        }

        return $next($request);
    }
}
