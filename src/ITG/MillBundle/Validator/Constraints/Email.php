<?php

namespace ITG\MillBundle\Validator\Constraints;

use ITG\MillBundle\Validator\ValidatorErrorCodes as E;

class Email extends \Symfony\Component\Validator\Constraints\Email
{
    public $message = 'This value is not a valid email address.{{ code }}';

    public $errorCode           = E::EMAIL;
    public $formatErrorCode     = E::EMAIL_FORMAT;
    public $mxErrorCode         = E::EMAIL_MX;
    public $hostErrorCode       = E::EMAIL_HOST;
}