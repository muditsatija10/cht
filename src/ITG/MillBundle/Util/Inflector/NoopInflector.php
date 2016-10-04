<?php

namespace ITG\MillBundle\Util\Inflector;

use FOS\RestBundle\Util\Inflector\InflectorInterface;

class NoopInflector implements InflectorInterface
{
    /**
     * Pluralizes noun.
     *
     * @param string $word
     *
     * @return string
     */
    public function pluralize($word)
    {
        return $word;
    }
}