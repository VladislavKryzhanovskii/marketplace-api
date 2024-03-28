<?php

declare(strict_types=1);

namespace App\Factory\User;

use App\Contracts\Factory\User\UserFactoryInterface;
use App\Contracts\Security\Service\Hasher\Password\User\UserPasswordHasherInterface;
use App\DTO\User\CreateUserDTO;
use App\Entity\User;

readonly class UserFactory implements UserFactoryInterface
{
    public function __construct(
        private UserPasswordHasherInterface $hasher,
    )
    {
    }

    public function create(CreateUserDTO $dto): User
    {
        return (new User())
            ->setEmail($dto->getEmail())
            ->setPassword($dto->getPassword(), $this->hasher);
    }
}