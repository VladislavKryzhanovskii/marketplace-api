<?php

namespace App\Contracts\Security\Service\User\Auth;

use App\Contracts\Security\Entity\AuthUserInterface;

interface AuthUserFetcherInterface
{
    public function getAuthUser(): AuthUserInterface;
}