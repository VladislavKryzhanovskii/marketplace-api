<?php

namespace App\Contracts\Factory\User;

use App\DTO\User\CreateUserDTO;
use App\Entity\User;

interface UserFactoryInterface
{
    public function create(CreateUserDTO $dto): User;
}