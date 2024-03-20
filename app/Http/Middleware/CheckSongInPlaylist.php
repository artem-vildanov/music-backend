<?php

namespace App\Http\Middleware;

use App\Repository\Interfaces\IPlaylistSongsRepository;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckSongInPlaylist
{
    public function __construct(
        private readonly IPlaylistSongsRepository $playlistSongsRepository
    ) {}

    public function handle(Request $request, Closure $next): Response
    {
        $songId = $request->route('songId');
        $playlistId = $request->route('playlistId');

        if ($this->playlistSongsRepository->checkSongInPlaylist($songId, $playlistId)) {
            return response()->json([
                'message' => 'song already contained in that playlist'
            ], 400);
        }

        return $next($request);
    }
}
