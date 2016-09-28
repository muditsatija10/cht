<?php

namespace Somtel\RemitOneBundle\Tests\Service;

use Symfony\Bundle\FrameworkBundle\Tests\TestCase;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Serializer;
use Faker;

/**
 * @group
 */

class DecoderTest extends KernelTestCase {

    public function setUp()
    {
        self::bootKernel();
        $this->container = self::$kernel->getContainer();

        $this->exampleXml = '
<?xml version="1.0" encoding="utf-8"?>
<response>
    <responseId>327</responseId>
    <status>SUCCESS</status>
    <result>
        <seed>667639034101739</seed>
    </result>
</response>
';

        $this->decoder = $this->container->get('r1.decoder');
    }
    public function testShouldThrowExceptionOnInvalidXml()
    {

    }
}