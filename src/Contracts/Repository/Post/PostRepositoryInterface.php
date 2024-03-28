<?php

namespace App\Contracts\Repository\Post;

use App\Entity\Post;
use Doctrine\Common\Collections\Selectable;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ObjectRepository;

interface PostRepositoryInterface extends ObjectRepository, Selectable
{
    public function save(Post $post): void;

    public function findByUlid(string $ulid): ?Post;

    /**
     * @return Paginator<int, Post>
     */
    public function getPaginator(): Paginator;
}