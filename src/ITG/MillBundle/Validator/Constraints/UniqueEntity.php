<?php

namespace ITG\MillBundle\Validator\Constraints;

use ITG\MillBundle\Validator\ValidatorErrorCodes as E;

class UniqueEntity extends \Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity
{
    public $errorCode = E::UNIQUE_ENTITY;
    public $message = "This value is already used.|100"; // This is a shitty hardcode, but currently no way around it
    
}