<?php

namespace App\Contracts\Security\Service\Hasher\Password\User;

use App\Entity\User;
use SensitiveParameter;

interface UserPasswordHasherInterface
{
    public function hash(User $user, #[SensitiveParameter] string $password): string;
}