<?php

namespace App\Tests\Resource\Fixture;

use App\DTO\User\CreateUserDTO;
use App\Factory\User\UserFactory;
use App\Tests\Util\FakerProvider;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class UserFixture extends Fixture
{
    use FakerProvider;

    /** @var string */
    public const REFERENCE = 'user';

    public function __construct(
        private readonly UserFactory $userFactory,
    )
    {
    }

    public function load(ObjectManager $manager): void
    {
        $dto = (new CreateUserDTO())
            ->setEmail($this->getFaker()->email())
            ->setPassword($this->getFaker()->password(minLength: 10));

        $user = $this->userFactory->create($dto);

        $manager->persist($user);
        $manager->flush();

        $this->addReference(self::REFERENCE, $user);
    }
}