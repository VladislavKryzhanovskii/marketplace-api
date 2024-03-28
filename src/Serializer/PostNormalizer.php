<?php

namespace App\Serializer;

use App\Entity\Image;
use App\Entity\Post;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class PostNormalizer implements NormalizerInterface
{

    private const ALREADY_CALLED = 'POST_NORMALIZER_ALREADY_CALLED';

    public function __construct(
        #[Autowire(service: 'serializer.normalizer.object')]
        private readonly NormalizerInterface $normalizer,
        #[Autowire(service: ImageNormalizer::class)]
        private readonly NormalizerInterface $imageNormalizer,
        private readonly Security $security,
    )
    {
    }

    public function normalize($object, ?string $format = null, array $context = []): array|string|int|float|bool|\ArrayObject|null
    {
        $context[self::ALREADY_CALLED] = true;

        return match ($context[AbstractNormalizer::GROUPS]) {
            'post:details' => $this->handleDetails($object, $format, $context),
            default => $this->normalizer->normalize($object, $format, $context),
        };
        
    }

    public function supportsNormalization($data, ?string $format = null, array $context = []): bool
    {

        if (isset($context[self::ALREADY_CALLED])) {
            return false;
        }

        return $data instanceof Post;
    }

    public function getSupportedTypes(?string $format): array
    {
        return [
            Post::class => true,
        ];
    }

    private function handleDetails(Post $post, ?string $format, array $context): array
    {
        return [
            'ulid' => $post->getUlid(),
            'title' => $post->getTitle(),
            'cost' => $post->getCost(),
            'description' => $post->getDescription(),
            'isOwner' => $post->getOwner()->getUserIdentifier() === $this->security->getUser()?->getUserIdentifier(),
            'imageUrls' => $post->getImages()->map(fn(Image $image): string => $this->imageNormalizer
                ->normalize($image, $format,$context)['contentUrl'])->toArray()
        ];
    }
}