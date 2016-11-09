<?php

namespace AppBundle\Pheanstalk;

use AppBundle\Service\PaymentService;
use Leezy\PheanstalkBundle\Proxy\PheanstalkProxyInterface;
use Pheanstalk\Pheanstalk;

class PaymentConsumer
{
    const TUBE_NAME = 'payment';
    const TIMEOUT = 10;

    /**
     * @var PheanstalkProxyInterface
     */
    private $client;

    /**
     * @var PaymentWorker
     */
    private $worker;

    /**
     * Consumer constructor.
     * @param $client Pheanstalk
     * @param $worker \Worker
     */
    public function __construct($client, $worker)
    {
        $this->client = $client;
        $this->worker = $worker;

    }

    /**
     * Queue processing
     */
    public function consumeQueue()
    {
        while ($reservedJob = $this->client->reserveFromTube(self::TUBE_NAME, self::TIMEOUT)) {
            try {
                // format job to class we need
                $job = new PaymentJob($reservedJob->getId(), $reservedJob->getData());

                $this->worker->executeJob($job);
                $this->client->delete($job);
            } catch (\Exception $e) {
                // todo: add more advanced way to remove broken job (with log, notification etc)
                $this->client->bury($job);
            }
        }
    }
}
