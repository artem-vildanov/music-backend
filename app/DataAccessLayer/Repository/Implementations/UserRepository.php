<?php


namespace App\DataAccessLayer\Repository\Implementations;

use App\DataAccessLayer\DbModels\User;
use App\DataAccessLayer\Repository\Interfaces\IUserRepository;
use App\Exceptions\DataAccessExceptions\DataAccessException;
use App\Exceptions\DataAccessExceptions\UserException;

class UserRepository implements IUserRepository
{
    public function getAll(): array {

    }

    /**
     * @throws DataAccessException
     */
    public function getById(int $userId): User
    {
        $user = User::query()->find($userId);

        if (!$user) {
            throw UserException::notFound($userId);
        }

        return $user;
    }

    public function create(string $name, string $password, string $email, string $role): User
    {
        $user = new User;
        $user->name = $name;
        $user->password = $password;
        $user->email = $email;
        $user->role = $role;
        $user->save();

        return $user;
    }

    /**
     * @throws DataAccessException
     */
    public function getByEmail(string $email): User
    {
        $user = User::query()->where('email', $email)->first();

        if (!$user) {
            throw UserException::notFoundByEmail($email);
        }

        return $user;
    }

    public function delete(int $userId): void
    {
        // TODO: Implement delete() method.
    }

    /**
     * @throws DataAccessException
     */
    public function update(int $userId, string $name, string $email, string $role): void
    {
        try {
            $user = $this->getById($userId);
        } catch (DataAccessException $e) {
            throw UserException::failedToUpdate($userId);
        }

        $user->name = $name;
        $user->email = $email;
        $user->role = $role;

        if (!$user->save()) {
            throw UserException::failedToUpdate($userId);
        }
    }
}


