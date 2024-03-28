<?php

declare(strict_types=1);

namespace App\Controller;

use App\Contracts\Factory\User\UserFactoryInterface;
use App\Contracts\Repository\User\UserRepositoryInterface;
use App\Contracts\Security\Service\User\Auth\AuthUserFetcherInterface;
use App\DTO\User\CreateUserDTO;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\VarDumper\VarDumper;

#[Route('/api/users', name: 'user_')]
class UserController extends AbstractController
{
    public function __construct(
        private readonly SerializerInterface      $serializer,
        private readonly ValidatorInterface       $validator,
        private readonly UserFactoryInterface     $userFactory,
        private readonly UserRepositoryInterface  $userRepository,
        private readonly AuthUserFetcherInterface $authUserFetcher,
    )
    {
    }

    #[Route(name: 'create', methods: [Request::METHOD_POST])]
    public function create(Request $request): JsonResponse
    {
        /** @var CreateUserDTO $dto */
        $dto = $this->serializer->deserialize($request->getContent(), CreateUserDTO::class, 'json');

        $errors = $this->validator->validate($dto);
        if (count($errors) > 0) {
            return $this->json(['message' => (string)$errors], Response::HTTP_BAD_REQUEST);
        }

        $user = $this->userFactory->create($dto);
        $this->userRepository->save($user);

        return $this->json(['ulid' => $user->getUlid()], status: Response::HTTP_CREATED);
    }

    #[Route('/me', name: 'me', methods: [Request::METHOD_GET])]
    public function details(): JsonResponse
    {
        $user = $this->authUserFetcher->getAuthUser();
        $json = $this->serializer->serialize($user, 'json', [
            AbstractNormalizer::GROUPS => 'user:details'
        ]);

        return JsonResponse::fromJsonString($json);
    }
}