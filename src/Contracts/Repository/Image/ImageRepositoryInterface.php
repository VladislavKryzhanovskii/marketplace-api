<?php

namespace App\Contracts\Repository\Image;

use App\Entity\Image;
use Doctrine\Common\Collections\Selectable;
use Doctrine\Persistence\ObjectRepository;

interface ImageRepositoryInterface extends ObjectRepository, Selectable
{
    public function save(Image $image): void;

    public function findByUlids(array $ulids);
}