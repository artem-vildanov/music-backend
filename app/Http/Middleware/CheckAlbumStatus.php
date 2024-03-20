<?php

namespace App\Http\Middleware;

use App\Exceptions\DataAccessExceptions\DataAccessException;
use App\Facades\AuthFacade;
use App\Repository\Interfaces\IAlbumRepository;
use App\Services\CacheServices\AlbumCacheService;
use App\Services\DomainServices\AlbumService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckAlbumStatus
{
    public function __construct(
        private readonly IAlbumRepository $albumRepository
    ) {}


    /**
     * Handle an incoming request.
     *
     * @param \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response) $next
     * @throws DataAccessException
     */
    public function handle(Request $request, Closure $next): Response
    {
        $albumId = $request->route('albumId');

        $album = $this->albumRepository->getById($albumId);

        $authUser = AuthFacade::getAuthInfo();

        if (
            $album->artist_id !== $authUser->artistId &&
            $album->status === 'private'
        ) {
            return response()->json([
                'error' => 'You are not permitted to access this resource.',
            ], 403);
        }

        return $next($request);
    }
}
