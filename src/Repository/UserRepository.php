<?php

namespace App\Repository;

use App\Mapper\UserMapper;
use App\User;

class UserRepository
{
    public UserMapper $mapper;
    public function __construct(UserMapper $mapper)
    {
        $this->mapper = $mapper;
    }

    public function save(User $user) {
        $this->mapper->insert($user);
    }

    public function login(User $user) {
        $this->mapper->getPassword($user);
    }
}