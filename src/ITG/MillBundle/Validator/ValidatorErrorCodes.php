<?php

namespace ITG\MillBundle\Validator;


class ValidatorErrorCodes
{
    const UNIQUE_ENTITY     = 100;

    const LENGTH            = 200;
    const LENGTH_MIN        = 201;
    const LENGTH_MAX        = 202;
    const LENGTH_EXACT      = 203;
    const LENGTH_CHARSET    = 204;

    const NOT_NULL          = 300;
    
    const NOT_BLANK         = 400;

    const EMAIL             = 500;
    const EMAIL_FORMAT      = 501;
    const EMAIL_MX          = 502;
    const EMAIL_HOST        = 503;
}