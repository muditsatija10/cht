<?php

namespace Somtel\RemitOneBundle\Payload;

use Aura\Payload\Payload as AuraPayload;

class Payload extends AuraPayload implements \JsonSerializable
{

    protected $exception;

    public function isSuccess()
    {
        return ($this->getStatus() === Status::SUCCESS);
    }

    public function isException()
    {
        return ($this->getStatus() === Status::EXCEPTION);
    }

    public function isFailure()
    {
        return ($this->getStatus() === Status::FAILURE);
    }

    public function jsonSerialize()
    {
        return $this->getOutput();
    }

    public function setException(\Exception $exception)
    {
        $this->exception = $exception;
        return $this;
    }

    public function getException()
    {
        return $this->exception;
    }

    public function getForClient()
    {
        $response = [
            "status" => $this->getStatus(),
            "message" => $this->getOutput(),
        ];
        if ($this->isException()) {
            $response["exception"] = $this->getException()->getMessage();
        }
        return $response;
    }
}
