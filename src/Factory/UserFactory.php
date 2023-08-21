<?php

namespace App\Factory;

use App\Entity\User;

class UserFactory
{
    public function createUser(
        string $email,
        string $username,
        string $password,
        array $roles = []
    ): User
    {
        $user = new User();
        $user->setEmail($email);
        $user->setUsername($username);
        $user->setPassword($password);
        $user->setRoles($roles);

        return $user;
    }
}
