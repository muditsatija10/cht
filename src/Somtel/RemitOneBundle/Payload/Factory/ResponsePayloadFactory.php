<?php

namespace Somtel\RemitOneBundle\Payload\Factory;

use Somtel\RemitOneBundle\Payload\Payload;
use Somtel\RemitOneBundle\Payload\Status;

class ResponsePayloadFactory extends BaseFactory
{

    public static function SuccessResponse()
    {
        $payload = self::newInstance();
        $payload->setStatus(Status::SUCCESS);
        return $payload;
    }
}
