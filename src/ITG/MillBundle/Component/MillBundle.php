<?php

namespace ITG\MillBundle\Component;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class MillBundle extends Bundle implements BundleVersionInterface
{
    protected $version;

    /**
     * @return string
     */
    public function getVersion()
    {
        return $this->version;
    }
}