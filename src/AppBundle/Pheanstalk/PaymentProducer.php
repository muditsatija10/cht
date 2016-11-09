<?php

namespace AppBundle\Pheanstalk;

use AppBundle\Pheanstalk\PaymentConsumer;
use AppBundle\Pheanstalk\PaymentJob;
use Leezy\PheanstalkBundle\Proxy\PheanstalkProxyInterface;
use Pheanstalk\Pheanstalk;

class PaymentProducer
{
    /**
     * @var PheanstalkProxyInterface
     */
    public $client;

    /**
     * Producer constructor.
     * @param $client Pheanstalk
     */
    public function __construct($client)
    {
        $this->client = $client;
    }

    /**
     * @param \AppBundle\Pheanstalk\PaymentJob $job
     */
    public function put(PaymentJob $job)
    {
        $this->client->putInTube(PaymentConsumer::TUBE_NAME, $job->getData());
    }
}
