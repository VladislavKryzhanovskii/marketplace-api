<?php

namespace App\Factory\Image;

use App\Contracts\Factory\Image\ImageFactoryInterface;
use App\Contracts\Security\Entity\AuthUserInterface;
use App\Entity\Image;
use App\Entity\User;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ImageFactory implements ImageFactoryInterface
{
    public function create(UploadedFile $file, AuthUserInterface $owner): Image
    {
        return (new Image())
            ->setFile($file)
            ->setOwner($owner);
    }
}