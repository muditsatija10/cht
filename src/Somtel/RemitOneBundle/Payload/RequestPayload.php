<?php

namespace Somtel\RemitOneBundle\Payload;

use Somtel\RemitOneBundle\Payload\Interfaces\RequestPayloadInterface;

class RequestPayload extends Payload implements RequestPayloadInterface
{
    private $sessionToken;
    private $username;
    private $params;

    public function getSessionToken()
    {
        return $this->sessionToken;
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function getParams()
    {
        return $this->params;
    }

    public function setSessionToken($sessionToken)
    {
        $this->sessionToken = $sessionToken;
        return $this;
    }

    public function setUsername($username)
    {
        $this->username = $username;
        return $this;
    }

    public function setParams($params)
    {
        $this->params = $params;
        return $this;
    }
}
