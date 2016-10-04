<?php

namespace Somtel\PipBundle\Tests\Service;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Serializer;

class CashinControllerTest extends WebTestCase
{

    public function setUp()
    {
        $this->urls = [];
        $this->urls["cashin"] = '/pip/cashins';

        $this->client = static::createClient();


        $this->container = static::$kernel->getContainer();
        $rootDir = $this->container->get('kernel')->getRootDir();
        $loader = new \Nelmio\Alice\Fixtures\Loader();
        $this->fixtures = $loader->load($rootDir.'/../src/Somtel/PipBundle/Tests/Fixtures/cashin.yml');
        $this->fixtures["valid_order"]->setVendorName($this->container->getParameter('pip_merchant_username'));

        $normalizer = new Serializer\Normalizer\GetSetMethodNormalizer();
        $this->fixtures["valid_order_array"] = $normalizer->normalize($this->fixtures["valid_order"]);
    }

    protected function makeRequest(
        $method,
        $url,
        $body = null,
        $headers = ["Content-Type" => 'application/json', "HTTP_X-AUTH-TOKEN" => 'test']
    ) {
        $this->client->request(
            $method,
            $url,
            array(),
            array(),
            $headers,
            $body
        );
        return $this->client->getResponse();
    }


    public function testCashinPostEndpointShouldExist()
    {
        $rsp = $this->makeRequest('POST', $this->urls["cashin"]);
        $this->assertFalse($rsp->isNotFound());
    }

    public function testCashInEndpointShouldNotReturnServerError()
    {
        $rsp = $this->makeRequest('POST', $this->urls["cashin"]);
        $this->assertLessThan(500, $rsp->getStatusCode());
    }

    public function testCashinPostingEmptyShouldReturn422Status()
    {
        $rsp = $this->makeRequest('POST', $this->urls["cashin"]);
        $this->assertEquals(422, $rsp->getStatusCode());
    }

    public function testInvalidCashinDataShoulrReturn422Status()
    {
        $rsp = $this->makeRequest('POST', $this->urls["cashin"], json_encode($this->fixtures["invalid_order"]));
        $this->assertEquals(422, $rsp->getStatusCode());
        $content = json_decode($rsp->getContent(), true);
        $this->assertArrayHasKey('errors', $content);
    }

    public function testValidCashinDataShouldReturnCreatedOrder()
    {
        $rsp = $this->makeRequest(
            'POST',
            $this->urls["cashin"],
            json_encode($this->fixtures["valid_order_array"])
        );
        $this->assertEquals(200, $rsp->getStatusCode());
        $content = json_decode($rsp->getContent(), true);
        $this->assertArrayHasKey('barcode', $content);
    }


    public function testCashinGetEndpointShouldReturnOrders()
    {
        $rsp = $this->makeRequest(
            'GET',
            $this->urls["cashin"]
        );
        $this->assertEquals(200, $rsp->getStatusCode());
        $content = json_decode($rsp->getContent(), true);
        $this->assertNotEmpty($content);
    }
    public function testCashinGetEndpointShouldReturnOrderWhenQueredWithBarcode()
    {
        $this->markTestIncomplete();
    }
}
