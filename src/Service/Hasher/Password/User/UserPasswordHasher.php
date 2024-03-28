<?php

namespace App\Service\Hasher\Password\User;

use App\Contracts\Security\Service\Hasher\Password\User\UserPasswordHasherInterface;
use App\Entity\User;
use SensitiveParameter;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface as InternalUserPasswordHasher;

class UserPasswordHasher implements UserPasswordHasherInterface
{
    public function __construct(
        private readonly InternalUserPasswordHasher $hasher,
    )
    {
    }


    public function hash(User $user, #[SensitiveParameter] string $password): string
    {
        return $this->hasher->hashPassword($user, $password);
    }
}