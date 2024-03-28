<?php

namespace App\Controller;

use App\Contracts\Factory\Post\PostFactoryInterface;
use App\Contracts\Repository\Post\PostRepositoryInterface;
use App\Contracts\Security\Service\User\Auth\AuthUserFetcherInterface;
use App\DTO\Post\CreatePostDTO;
use App\Entity\Post;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('api/posts', name: 'post_')]
class PostController extends AbstractController
{
    public function __construct(
        private readonly SerializerInterface      $serializer,
        private readonly ValidatorInterface       $validator,
        private readonly AuthUserFetcherInterface $authUserFetcher,
        private readonly PostFactoryInterface     $postFactory,
        private readonly PostRepositoryInterface  $postRepository,
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

        $post = $this->postFactory->build($dto, $this->authUserFetcher->getAuthUser());
        $this->postRepository->save($post);

        return $this->json(['ulid' => $post->getUlid()], Response::HTTP_CREATED);
    }

    #[Route('/{ulid}', name: 'remove', methods: [Request::METHOD_DELETE])]
    public function remove(string $ulid): Response
    {
        $post = $this->postRepository->findByUlid($ulid);
        $this->postRepository->remove($post);

        return new Response(status: Response::HTTP_NO_CONTENT);
    }

    #[Route('/{ulid}', name: 'update', methods: [Request::METHOD_PUT])]
    public function update(Request $request, string $ulid): Response
    {
        $post = $this->postRepository->findByUlid($ulid);

        /** @var CreatePostDTO $dto */
        $dto = $this->serializer->deserialize($request->getContent(), CreatePostDTO::class, 'json');

        $errors = $this->validator->validate($dto);
        if (count($errors) > 0) {
            return $this->json(['message' => (string)$errors], Response::HTTP_BAD_REQUEST);
        }

        $this->postFactory->build($dto, $this->authUserFetcher->getAuthUser(), $post);
        $this->postRepository->save($post);

        return new Response(status: Response::HTTP_NO_CONTENT);
    }

    #[Route(name: 'paginated', methods: [Request::METHOD_GET])]
    public function get(Request $request): JsonResponse
    {
        $paginator = $this->postRepository->getPaginator();
        $data = [
            'totalCount' => $total = count($paginator),
            'pageCount' => (int)ceil($total / $request->query->getInt('limit', 10)),
            'result' => iterator_to_array($paginator)
        ];

        $json = $this->serializer->serialize($data, 'json', [
            AbstractNormalizer::GROUPS => 'post:details'
        ]);

        return JsonResponse::fromJsonString($json);

    }
}