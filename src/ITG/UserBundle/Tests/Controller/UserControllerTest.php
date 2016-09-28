<?php

namespace ITG\UserBundle\Tests\Controller;

use ITG\UserBundle\DataFixtures\ORM\LoadUserData;
use Liip\FunctionalTestBundle\Test\WebTestCase;

class UserControllerTest extends WebTestCase
{
    private $fixtures;
    private $token;

    public function setUp()
    {
        $this->fixtures = $this->loadFixtures([LoadUserData::class])->getReferenceRepository();
        $this->token = $this->fixtures->getReference('token')->getToken();
    }

    public function testUserList()
    {
        $client = static::makeClient([], ['HTTP_X_AUTH_TOKEN' => $this->token]);
        $client->request('GET', '/users');

        $this->assertStatusCode(200, $client);
        $object = json_decode($client->getResponse()->getContent());

        $this->assertEquals($object->count, 3);
    }

    public function testUserGet()
    {
        $user = $this->fixtures->getReference('user-admin');

        $client = static::makeClient([], ['HTTP_X_AUTH_TOKEN' => $this->token]);
        $client->request('GET', '/user/' . $user->getId());

        $this->assertStatusCode(200, $client);

        $object = json_decode($client->getResponse()->getContent());

        $this->assertEquals($object->username, $user->getUsername());

        $client->request('GET', '/user/0');
        $this->assertStatusCode(404, $client);
    }

    public function testUserPost()
    {
        $user = [
            'username' => 'testUser',
            'password' => 'testPassword',
            'roles' => ['TEST_ROLE']
        ];

        // test successful post
        $client = static::makeClient([], ['HTTP_X_AUTH_TOKEN' => $this->token]);
        $client->request('POST', '/user', $user);
        $this->assertStatusCode(201, $client);

        $this->assertEquals($user['username'], json_decode($client->getResponse()->getContent(), true)['username']);

        // test bad form
        $client->request('POST', '/user', []);
        $this->assertStatusCode(400, $client);

        // test duplicate
        $client->request('POST', '/user', $user);
        $this->assertStatusCode(400, $client);
    }

    public function testUserPut()
    {
        $u = $this->fixtures->getReference('user-test');
        $user = [
            'username' => 'otherUser',
            'password' => 'otherPassword'
        ];

        $client = static::makeClient([], ['HTTP_X_AUTH_TOKEN' => $this->token]);

        // test success
        $client->request('PUT', '/user/' . $u->getId(), $user);
        $this->assertStatusCode(200, $client);
        $this->assertEquals($u->getUsername(), json_decode($client->getResponse()->getContent(), true)['username']);

        // test not found
        $client->request('PUT', '/user/0', $user);
        $this->assertStatusCode(404, $client);

        // test bad form
        $client->request('POST', '/user', []);
        $this->assertStatusCode(400, $client);

        // test duplicate
        $client->request('POST', '/user', $user);
        $this->assertStatusCode(400, $client);
    }

    public function testUserDelete()
    {
        $user = $this->fixtures->getReference('user-test');

        $client = static::makeClient([], ['HTTP_X_AUTH_TOKEN' => $this->token]);

        // test success
        $client->request('DELETE', '/user/' . $user->getId());
        $this->assertStatusCode(204, $client);

        // try to find deleted user
        $client->request('DELETE', '/user/' . $user->getId());
        $this->assertStatusCode(404, $client);
    }
}