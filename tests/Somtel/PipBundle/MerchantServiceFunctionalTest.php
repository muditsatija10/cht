<?php

namespace Somtel\PipBundle\Tests\Service;

use Symfony\Bundle\FrameworkBundle\Tests\TestCase;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Faker;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Exception\RequestException;
use Symfony\Component\Serializer;

class MerchantServiceFunctionalTest extends KernelTestCase
{

    public function setUp()
    {
        static::bootKernel();
        $this->container = static::$kernel->getContainer();
        $this->cashinFacade = $this->container->get('somtel_pip.cashin_facade');
        $this->util = $this->container->get('somtel_pip.util');
        $this->defaultKeys = ['vendorName', 'vendorOrderReference', 'orderValue', 'orderCurrencyCode', 'customerEmail', 'customerName'];

        $rootDir = $this->container->get('kernel')->getRootDir();
        $loader = new \Nelmio\Alice\Fixtures\Loader();
        $this->fixtures = $loader->load($rootDir.'/../src/Somtel/PipBundle/Tests/Fixtures/cashin.yml');

        $normalizer = new Serializer\Normalizer\GetSetMethodNormalizer();
        $this->fixtures["signed_order_array"] = $normalizer->normalize($this->fixtures["signed_order"]);
    }

    public function testPreparedOrderShouldHaveProperKeys()
    {
        $invalidOrder = ["vendorName" => 'test'];

        $preparedOrder = $this->util->prepareOrder($invalidOrder);
        $this->assertEquals($invalidOrder["vendorName"], $preparedOrder["vendorName"]);
        foreach ($this->defaultKeys as $key) {
            $this->assertArrayHasKey($key, $preparedOrder);
        }
    }

    public function testSignedOrderShouldNotBeOverwritten()
    {
        $preparedOrder = $this->util->prepareOrder($this->fixtures["signed_order_array"]);
        $this->assertEquals($this->fixtures["signed_order_array"], $preparedOrder);
    }
}
