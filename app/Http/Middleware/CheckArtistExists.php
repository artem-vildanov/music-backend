<?php

namespace App\Http\Middleware;

use App\DataAccessLayer\Repository\Interfaces\IArtistRepository;
use App\Exceptions\DataAccessExceptions\DataAccessException;
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
        $artistId = $request->route('artistId');
        $this->artistRepository->getById($artistId);

        return $next($request);
    }


}
