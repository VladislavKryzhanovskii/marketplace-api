<?php

namespace App\Contracts\Factory\Post;

use App\Contracts\Security\Entity\AuthUserInterface;
use App\DTO\Post\CreatePostDTO;
use App\Entity\Post;


interface PostFactoryInterface
{
    public function build(CreatePostDTO $dto, AuthUserInterface $owner, Post $post = new Post()): Post;
}