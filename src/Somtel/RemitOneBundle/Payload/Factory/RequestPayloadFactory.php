<?php

namespace Somtel\RemitOneBundle\Payload\Factory;

use Somtel\RemitOneBundle\Payload\RequestPayload;

class RequestPayloadFactory extends BaseFactory
{
    public function createFromRequest($request)
    {
    }

    public function createFromArray($array)
    {
        $payload = new RequestPayload();
        if (array_key_exists('username', $array)) {
            $payload->setUsername($array["username"]);
        }

        if (array_key_exists('session_token', $array)) {
            $payload->setSessionToken($array["session_token"]);
        }

        if (array_key_exists('sessionToken', $array)) {
            $payload->setSessionToken($array["session_token"]);
        }
        return $payload;
    }
}
