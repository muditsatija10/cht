<?php

namespace WoraPayBundle\Tests\Controller;

use AppBundle\Entity\User;
use Somtel\WoraPayBundle\Entity\CardToken;
use Somtel\WoraPayBundle\Repository\CardTokenRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class CardServiceTest extends KernelTestCase
{
    private $container;

    public function setUp()
    {
        parent::setUp();

        $kernel = static::createKernel();
        $kernel->boot();

        $this->container = $kernel->getContainer();
        $this->container->get('doctrine.dbal.default_connection')->beginTransaction();
    }

    public function testStore()
    {
        $cardService = $this->container->get('wora_pay.card');
        $entityManager = $this->container->get('doctrine.orm.entity_manager');

        $user = $entityManager->getRepository(User::class)->find(1);

        $tokenData = [
            'id' => rand(),
            'last4' => rand(1000, 9999),
            'status' => 'active',
            'address_line1' => 'Test str. 1',
            'address_city' => 'New-York',
            'address_state' => 'NY'
        ];

        $cardService->store($tokenData, $user);

        $cardRepository = $this->container->get('doctrine.orm.entity_manager')->getRepository(CardToken::class);

        $this->assertNotEmpty($cardRepository->findAll());
    }

    public function testUpdateCard()
    {
        // todo: need to implement this test
    }

    public function testUpdateAddress()
    {
        // todo: need to implement this test
    }

    public function testDelete()
    {
        // todo: need to implement this test
    }

    public function tearDown()
    {
        parent::tearDown();

        $this->container->get('doctrine.dbal.default_connection')->rollback();
    }
}
