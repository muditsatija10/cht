<?php

namespace Somtel\PipBundle\Tests\Service;

use Symfony\Bundle\FrameworkBundle\Tests\TestCase;
use Somtel\PipBundle\Service;
use Faker;
use GuzzleHttp\Psr7;

class TransporterServiceUnitTest extends TestCase
{

    public function setUp()
    {
        $faker = Faker\Factory::create();
        $this->fakeString = $faker->text;
        $this->fakeArray = [
            "some" => $faker->name,
            "random" => $faker->text,
            "data" => $faker->phoneNumber,
        ];
        $this->response = new Psr7\Response(200, [], $this->fakeString);
        $this->jsonResponse = new Psr7\Response(200, ['Content-Type' => 'application/json'], json_encode($this->fakeArray));

        $this->transporter = new Service\Transporter('mock', 'mock', 'mock');
        $this->util = new Service\Util('mock');

        $loader = new \Nelmio\Alice\Fixtures\Loader();
        $this->fixtures = $loader->load(__DIR__.'/../Fixtures/cashin.yml');
    }

    public function testTextResponseShouldBeDecoded()
    {
        $decodedResponse = $this->transporter->decodeResponse($this->response);
        $this->assertEquals($this->fakeString, $decodedResponse);
    }

    public function testJsonResponseShouldBeDecoded()
    {
        $decodedResponse = $this->transporter->decodeResponse($this->jsonResponse);
        $this->assertEquals($this->fakeArray, $decodedResponse);
    }

    public function testOrderSignatureShouldBeValid()
    {
        $order = $this->fixtures["signed_order"];
        $validSignature = $order->getSignature();
        $generatedSignature = $this->util->getOrderSignature(
            $order->getVendorName(),
            $order->getOrderValue(),
            $order->getCustomerEmail(),
            $order->getVendorOrderReference(),
            'slaptesnis' // This was used to sign "signed_order" on pip-it.net
        );
        $this->assertEquals($validSignature, $generatedSignature);
    }
}
