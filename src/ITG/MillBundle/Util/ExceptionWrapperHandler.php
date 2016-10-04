<?php

namespace ITG\MillBundle\Util;

class ExceptionWrapperHandler
    extends \FOS\RestBundle\View\ExceptionWrapperHandler // implements ExceptionWrapperHandlerInterface
{
    /**
     * {@inheritdoc}
     */
    public function wrap($data)
    {
        //dump($data);
        return new ExceptionWrapper($data);
    }
}