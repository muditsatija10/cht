<?php

namespace ITG\UserBundle\Tests\Controller;

use ITG\UserBundle\DataFixtures\ORM\LoadUserData;
use Liip\FunctionalTestBundle\Test\WebTestCase;

class LoginControllerTest extends WebTestCase
{
    private $fixtures;

    public function setUp()
    {
        $this->fixtures = $this->loadFixtures([LoadUserData::class])->getReferenceRepository();
    }

    public function testLogin()
    {
        $client = static::makeClient();

        // test success
        $client->request('POST', '/login', [
            'username' => 'login',
            'password' => 'password'
        ]);
        $this->assertStatusCode(200, $client);

        // test bad data
        $client->request('POST', '/login', [
            'username' => 'bad',
            'password' => 'bad'
        ]);
        $this->assertStatusCode(404, $client);

        // test bad form
        $client->request('POST', '/login', []);
        $this->assertStatusCode(400, $client);
    }

}