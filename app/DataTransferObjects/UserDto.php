<?php

namespace App\DataTransferObjects;

class UserDto
{
    public int $id;
    public string $name;

    public string $email;

    public string $password;

    public string $role;
}
