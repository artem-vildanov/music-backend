<?php

namespace App\Http\Middleware;

use App\DataAccessLayer\Repository\Interfaces\IGenreRepository;
use App\Exceptions\DataAccessExceptions\DataAccessException;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckGenreExists
{
    public function __construct(
        private readonly IGenreRepository $genreRepository
    ) {}

    /**
     * @throws DataAccessException
     */
    public function handle(Request $request, Closure $next): Response
    {
        $genreId = $request->route('genreId');
        $this->genreRepository->getById($genreId);

        return $next($request);
    }
}
