<?php

namespace Somtel\RemitOneBundle\Payload\Factory;

use Aura\Payload\PayloadFactory;
use Somtel\RemitOneBundle\Payload;

class BaseFactory extends PayloadFactory
{
    public function newInstance()
    {
        return new Payload\Payload();
    }
}
