<?php

namespace Somtel\RemitOneBundle\Payload\Interfaces;

interface RequestPayloadInterface
{
    public function getSessionToken();
    public function setSessionToken($sessionToken);

    public function getUsername();
    public function setUsername($username);

    public function getParams();
    public function setParams($params);
}