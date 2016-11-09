<?php

namespace AppBundle\Pheanstalk;

use AppBundle\Service\PaymentService;

class PaymentWorker
{
    /**
     * @var PaymentService
     */
    private $paymentService;

    /**
     * Worker constructor.
     * @param $paymentService PaymentService
     */
    public function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }

    /**
     * @param $job PaymentJob
     */
    public function executeJob(PaymentJob $job)
    {
        $this->paymentService->createPayment($job->getDataAsArray());
    }
}
