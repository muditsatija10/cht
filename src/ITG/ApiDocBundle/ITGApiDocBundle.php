<?php

namespace ITG\ApiDocBundle;

use ITG\MillBundle\Component\MillBundle;

class ITGApiDocBundle extends MillBundle
{
    protected $version = '0.1.0';

    public function getParent()
    {
        return 'NelmioApiDocBundle';
    }
}