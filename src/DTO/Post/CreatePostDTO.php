<?php

namespace App\DTO\Post;

use Symfony\Component\Validator\Constraints as Assert;

final class CreatePostDTO
{
    #[Assert\NotBlank]
    #[Assert\PositiveOrZero]
    private int $cost;

    #[Assert\NotBlank]
    #[Assert\Length(max: 255)]
    private string $title;

    #[Assert\Length(max: 4000)]
    private ?string $description = null;

    /** @var string[] $imageUlids */
    #[Assert\All([new Assert\Ulid()])]
    private array $imageUlids;


    public function getCost(): int
    {
        return $this->cost;
    }

    public function setCost(int $cost): CreatePostDTO
    {
        $this->cost = $cost;
        return $this;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): CreatePostDTO
    {
        $this->title = $title;
        return $this;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): CreatePostDTO
    {
        $this->description = $description;
        return $this;
    }

    public function getImageUlids(): array
    {
        return $this->imageUlids;
    }

    public function setImageUlids(array $imageUlids): CreatePostDTO
    {
        $this->imageUlids = $imageUlids;

        return $this;
    }
}