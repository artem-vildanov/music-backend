<?php

namespace App\Http\Middleware;

use App\Exceptions\DataAccessExceptions\DataAccessException;
use App\Repository\Interfaces\IAlbumRepository;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckAlbumExists
{
    public function __construct(
        private readonly IAlbumRepository $albumRepository
    ) {}

    /**
     * @throws DataAccessException
     */
    public function handle(Request $request, Closure $next): Response
    {
        $requestAlbumId = (int)$request->route('albumId');

        $album = $this->albumRepository->getById($requestAlbumId);

        return $next($request);
    }
}
