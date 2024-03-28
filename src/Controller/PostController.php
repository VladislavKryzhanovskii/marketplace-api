<?php

namespace App\Controller;

use App\Contracts\Factory\Post\PostFactoryInterface;
use App\Contracts\Repository\Post\PostRepositoryInterface;
use App\Contracts\Security\Service\User\Auth\AuthUserFetcherInterface;
use App\Contracts\Service\Post\PostServiceInterface;
use App\DTO\Post\CreatePostDTO;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\UidNormalizer;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\VarDumper\VarDumper;

#[Route('api/posts', name: 'post_')]
class PostController extends AbstractController
{
    public function __construct(
        private readonly SerializerInterface      $serializer,
        private readonly ValidatorInterface       $validator,
        private readonly AuthUserFetcherInterface $authUserFetcher,
        private readonly PostFactoryInterface     $postFactory,
        private readonly PostRepositoryInterface  $postRepository,
        private readonly PostServiceInterface     $postService,
    )
    {
    }

    #[Route(name: 'create', methods: [Request::METHOD_POST])]
    public function create(Request $request): JsonResponse
    {
        /** @var CreatePostDTO $dto */
        $dto = $this->serializer->deserialize($request->getContent(), CreatePostDTO::class, 'json');

        $errors = $this->validator->validate($dto);
        if (count($errors) > 0) {
            return $this->json(['message' => (string)$errors], Response::HTTP_BAD_REQUEST);
        }

        $post = $this->postFactory->create($dto, $this->authUserFetcher->getAuthUser());
        $this->postRepository->save($post);

        return $this->json(['ulid' => $post->getUlid()], Response::HTTP_CREATED);
    }

    #[Route(name: 'paginated', methods: [Request::METHOD_GET])]
    public function get(Request $request): JsonResponse
    {

        $paginator = $this->postService->getPaginator();
    }
}