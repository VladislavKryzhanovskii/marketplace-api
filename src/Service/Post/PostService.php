<?php

namespace App\Service\Post;

use App\Contracts\Repository\Post\PostRepositoryInterface;
use App\Contracts\Service\Post\PostServiceInterface;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Symfony\Component\HttpFoundation\RequestStack;

class PostService implements PostServiceInterface
{
    public function __construct(
        private readonly PostRepositoryInterface   $postRepository,
        private readonly PaginatorConditionBuilder $conditionBuilder,
    )
    {

    }


    public function getPaginator(): Paginator
    {
//        $request = $this->requestStack->getCurrentRequest();
        $this->conditionBuilder->run();
        return $this->postRepository->getPaginatedUsers(

        );
    }
}