<?php

namespace App\Contracts\Factory\Image;

use App\Contracts\Security\Entity\AuthUserInterface;
use App\Entity\Image;
use App\Entity\User;
use Symfony\Component\HttpFoundation\File\UploadedFile;

interface ImageFactoryInterface
{
    public function build(UploadedFile $file, AuthUserInterface $owner): Image;
}