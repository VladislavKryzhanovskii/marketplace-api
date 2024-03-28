<?php

namespace App\Tests\Util;

use App\Entity\User;
use App\Tests\Resource\Fixture\UserFixture;
use Liip\TestFixturesBundle\Services\DatabaseToolCollection;
use Liip\TestFixturesBundle\Services\DatabaseTools\AbstractDatabaseTool;
use Symfony\Component\DependencyInjection\Container;

trait FixtureProvider
{
    public function getDatabaseTool(): AbstractDatabaseTool
    {
        return static::getContainer()->get(DatabaseToolCollection::class)->get();
    }

    public function loadUserFixture(): User
    {
        $executor = $this->getDatabaseTool()->loadFixtures([UserFixture::class]);

        return $executor->getReferenceRepository()->getReference(UserFixture::REFERENCE, User::class);
    }

    abstract protected static function getContainer(): Container;

}