<?php

namespace App\Repository\Interfaces;

use App\Exceptions\DataAccessExceptions\DataAccessException;
use App\Models\User;

interface IUserRepository {

    /**
     * @param int $userId
     * @throws DataAccessException
     * @return User
     */
    public function getById(int $userId): User;

    /**
     * @param string $email
     * @throws DataAccessException
     * @return User
     */
    public function getByEmail(string $email): User;


    /**
     * @param string $name
     * @param string $password
     * @param string $email
     * @param string $role
     * @throws DataAccessException
     * @return User
     */
    public function create(string $name, string $password, string $email, string $role): User;

    /**
     * @param int $userId
     * @throws DataAccessException
     * @return void
     */
    public function delete(int $userId): void;

    /**
     * @param int $userId
     * @param string $name
     * @param string $email
     * @param string $role
     * @throws DataAccessException
     * @return void
     */
    public function update(int $userId, string $name, string $email, string $role): void;
}
