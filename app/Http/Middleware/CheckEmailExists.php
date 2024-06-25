<?php

namespace App\Http\Middleware;

use App\DataAccessLayer\Repository\Interfaces\IUserRepository;
use App\Exceptions\DataAccessExceptions\DataAccessException;
use App\Exceptions\DataAccessExceptions\UserException;
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
        $email = $request->input('email');
        try {
            $this->userRepository->getByEmail($email);
        } catch (DataAccessException) {
            return $next($request);
        }
        throw UserException::emailExists($email);
    }
}
