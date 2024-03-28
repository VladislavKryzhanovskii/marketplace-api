<?php

namespace App\Factory\Post;

use App\Contracts\Factory\Post\PostFactoryInterface;
use App\Contracts\Repository\Image\ImageRepositoryInterface;
use App\Contracts\Security\Entity\AuthUserInterface;
use App\DTO\Post\CreatePostDTO;
use App\Entity\Post;

class PostFactory implements PostFactoryInterface
{
    public function __construct(
        private readonly ImageRepositoryInterface $imageRepository,
    )
    {
    }

    public function build(CreatePostDTO $dto, AuthUserInterface $owner, Post $post = new Post()): Post
    {
        $post
            ->setOwner($owner)
            ->setCost($dto->getCost())
            ->setTitle($dto->getTitle())
            ->setDescription($dto->getDescription());

        if (empty($dto->getImageUlids())) {
            return $post->clearImages();
        }

        foreach ($this->imageRepository->findByUlids($dto->getImageUlids()) as $image) {
            $post->addImage($image);
        }

        return $post;
    }
}