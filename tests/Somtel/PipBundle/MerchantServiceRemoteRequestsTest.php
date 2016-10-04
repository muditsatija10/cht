<?php

namespace Somtel\PipBundle\Tests\Service;

use Symfony\Bundle\FrameworkBundle\Tests\TestCase;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Serializer;
use Faker;

/**
 * @group remote
 */
class MerchantServiceRemoteRequestsTest extends KernelTestCase
{

    public function setUp()
    {
        static::bootKernel();
        $this->container = static::$kernel->getContainer();
        $this->transporter = $this->container->get('somtel_pip.transporter');

        $rootDir = $this->container->get('kernel')->getRootDir();
        $loader = new \Nelmio\Alice\Fixtures\Loader();
        $this->fixtures = $loader->load($rootDir.'/../src/Somtel/PipBundle/Tests/Fixtures/cashin.yml');

        $normalizer = new Serializer\Normalizer\GetSetMethodNormalizer();
        $this->fixtures["signed_order_array"] = $normalizer->normalize($this->fixtures["signed_order"]);
    }

    public function testClientShouldLoginWithMerchantCredentials()
    {
        $responseStatusCode = $this->transporter->login()->lastResponse->getStatusCode();
        $this->assertEquals(200, $responseStatusCode);
    }

    public function testClientShouldCreateCashinOrder()
    {
        $signedOrder = $this->fixtures["signed_order_array"];
        $createdOrder = $this->transporter->login()->createOrder($signedOrder);
        $this->assertNotFalse($createdOrder);
        $this->assertNotNull($createdOrder["barcode"]);
        $this->assertEquals($signedOrder["customerEmail"], $createdOrder["customerEmail"]);
    }

    /**
     * @depends testClientShouldCreateCashinOrder
     */
    public function testClientShouldLoadPendingOrders()
    {
        $pendingOrders = $this->transporter->login()->getPendingOrders();
        $this->assertNotEmpty($pendingOrders);
    }

    public function testClientShouldNotCreateInvalidCashOrder()
    {
        $invalidOrder = $this->fixtures["invalid_order"];
        $createdOrder = $this->transporter->login()->createOrder($invalidOrder);
        $this->assertFalse($createdOrder);
    }
}
