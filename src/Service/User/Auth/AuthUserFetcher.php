<?php

namespace App\Service\User\Auth;

use App\Contracts\Security\Entity\AuthUserInterface;
use App\Contracts\Security\Service\User\Auth\AuthUserFetcherInterface;
use InvalidArgumentException;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

readonly class AuthUserFetcher implements AuthUserFetcherInterface
{
    public function __construct(
        private Security $security,
    )
    {
    }

    public function getAuthUser(): AuthUserInterface
    {
        $user = $this->security->getUser() ?? throw new NotFoundHttpException('User is not found');

        if ($user instanceof AuthUserInterface) {
            return $user;
        }

        throw new InvalidArgumentException(sprintf('Invalid user type %s', get_class($user)));
    }
}