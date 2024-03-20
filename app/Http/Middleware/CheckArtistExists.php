<?php

namespace App\Http\Middleware;

use App\Exceptions\DataAccessExceptions\DataAccessException;
use App\Repository\Interfaces\IAlbumRepository;
use App\Repository\Interfaces\IArtistRepository;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckArtistExists
{
    public function __construct(
        private readonly IArtistRepository $artistRepository
    ) {}

    /**
     * @throws DataAccessException
     */
    public function handle(Request $request, Closure $next): Response
    {
        $artistId = (int)$request->route('artistId');
        $this->artistRepository->getById($artistId);

        return $next($request);
    }


}
