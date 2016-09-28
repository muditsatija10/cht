<?php

namespace Somtel\RemitOneBundle\Interfaces;

interface Decoder
{
    public function decodeResponse($xmlString);
}
