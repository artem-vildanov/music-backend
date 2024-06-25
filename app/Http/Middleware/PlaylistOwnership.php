<?php

namespace App\Http\Middleware;

use App\DataAccessLayer\Repository\Interfaces\IPlaylistRepository;
use App\Exceptions\DataAccessExceptions\DataAccessException;
use App\Facades\AuthFacade;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PlaylistOwnership
{
    public function __construct(
        private readonly IPlaylistRepository $playlistRepository
    ) {}

    /**
     * @throws DataAccessException
     */
    public function handle(Request $request, Closure $next): Response
    {
        $playlistId = $request->route('playlistId');

        $playlist = $this->playlistRepository->getById($playlistId);

        $authUserId = AuthFacade::getUserId();
        if ($playlist->userId !== $authUserId) {
            return response()->json([
                'message' => 'not permitted to access this resource'
            ], 403);
        }

        return $next($request);
    }
}
