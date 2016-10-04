<?php

namespace ITG\MillBundle\Validator\Constraints;


use ITG\MillBundle\Validator\ValidatorErrorCodes as E;

class NotBlank extends \Symfony\Component\Validator\Constraints\NotBlank
{
    public $message = 'This value should not be blank.{{ code }}';
    public $errorCode = E::NOT_BLANK;
}