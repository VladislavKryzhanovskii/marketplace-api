<?php

namespace App\Contracts\Repository\User;

use App\Entity\User;
use Doctrine\Common\Collections\Selectable;
use Doctrine\Persistence\ObjectRepository;

interface UserRepositoryInterface extends ObjectRepository, Selectable
{
    public function save(User $user): void;

    public function remove(User $user): void;

    public function findByUlid(string $ulid): ?User;
}