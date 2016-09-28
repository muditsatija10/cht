<?php

namespace ITG\MillBundle\Validator\Constraints;


use ITG\MillBundle\Validator\ValidatorErrorCodes as E;

class NotNull extends \Symfony\Component\Validator\Constraints\NotNull
{
    public $message = 'This value should not be null.{{ code }}';
    public $errorCode = E::NOT_NULL;
}