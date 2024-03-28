<?php

namespace App\Contracts\Service\Post;

use App\Entity\Post;
use Doctrine\ORM\Tools\Pagination\Paginator;

interface PostServiceInterface
{
    /**
     * @return Paginator<int, Post>
     */
    public function getPaginator(): Paginator;
}