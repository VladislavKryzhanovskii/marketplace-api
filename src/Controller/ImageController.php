<?php

namespace App\Controller;

use App\Contracts\Factory\Image\ImageFactoryInterface;
use App\Contracts\Repository\Image\ImageRepositoryInterface;
use App\Contracts\Security\Service\User\Auth\AuthUserFetcherInterface;
use App\Repository\ImageRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;
use Vich\UploaderBundle\Templating\Helper\UploaderHelper;

#[Route('/api/images', name: 'image_')]
class ImageController extends AbstractController
{
    public function __construct(
        private readonly ImageFactoryInterface    $imageFactory,
        private readonly ImageRepositoryInterface $imageRepository,
        private readonly SerializerInterface      $serializer,
        private readonly AuthUserFetcherInterface $authUserFetcher,
    )
    {
    }

    #[Route(name: 'create', methods: [Request::METHOD_POST])]
    public function create(Request $request): JsonResponse
    {
        $file = $request->files->get('file');
        if (is_null($file)) {
            return $this->json(['message' => '"file" is required'], Response::HTTP_BAD_REQUEST);
        }

        $image = $this->imageFactory->build($file, $this->authUserFetcher->getAuthUser());

        $this->imageRepository->save($image);

        $json = $this->serializer->serialize($image, 'json', [
            AbstractNormalizer::GROUPS => 'image:get'
        ]);

        return JsonResponse::fromJsonString($json, Response::HTTP_CREATED);
    }
}