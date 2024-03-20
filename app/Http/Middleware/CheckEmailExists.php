<?php

namespace App\Http\Middleware;

use App\Exceptions\DataAccessExceptions\DataAccessException;
use App\Repository\Interfaces\IUserRepository;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckEmailExists
{
    public function __construct(
        private readonly IUserRepository $userRepository,
    ) {}

    /**
     * Handle an incoming request.
     *
     * @param \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response) $next
     * @throws DataAccessException
     */
    public function handle(Request $request, Closure $next): Response
    {
//        try {
//            $this->userRepository->getByEmail($request->input('email'));
//        } catch (DataAccessException $e) {
//
//        }

        return $next($request);
    }
}
