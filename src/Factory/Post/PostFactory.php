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

    public function create(CreatePostDTO $dto, AuthUserInterface $owner): Post
    {
        $post = (new Post())
            ->setOwner($owner)
            ->setCost($dto->getCost())
            ->setTitle($dto->getTitle())
            ->setDescription($dto->getDescription());

        foreach ($this->imageRepository->findByUlids($dto->getImageUlids()) as $image) {
            $post->addImage($image);
        }

        return $post;
    }
}