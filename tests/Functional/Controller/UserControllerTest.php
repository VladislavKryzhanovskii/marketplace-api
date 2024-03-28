<?php

namespace App\Tests\Functional\Controller;

use App\Tests\Util\FixtureProvider;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;

class UserControllerTest extends WebTestCase
{
    use FixtureProvider;

    private KernelBrowser $client;

    protected function setUp(): void
    {
        $this->client = static::createClient();
    }

    public function testGetMeAction(): void
    {
        $user = $this->loadUserFixture();

        $this->client->request(Request::METHOD_POST, '/api/auth/token/login',
            server: ['CONTENT_TYPE' => 'application/json'],
            content: json_encode(['email' => $user->getEmail(), 'password' => $user->getPassword()])
        );

        $data = json_decode($this->client->getResponse()->getContent(), true);

        $this->client->setServerParameter('HTTP_AUTHORIZATION', sprintf('Bearer %s', $data['token']));

        $this->client->request(Request::METHOD_GET, '/api/users/me');

        $data = json_decode($this->client->getResponse()->getContent(), true);

        $this->assertEquals($user->getEmail(), $data['email']);
    }
}