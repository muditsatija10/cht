<?php

namespace Somtel\RemitOneBundle\Service;

class Decoder
{
    public function decode($xmlString)
    {
        return \Cake\Utility\Xml::toArray(\Cake\Utility\Xml::build($xmlString))["response"];
    }
}
