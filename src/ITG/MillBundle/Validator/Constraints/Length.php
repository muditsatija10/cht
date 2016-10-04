<?php

namespace ITG\MillBundle\Validator\Constraints;


use ITG\MillBundle\Validator\ValidatorErrorCodes as E;

class Length extends \Symfony\Component\Validator\Constraints\Length
{
    public $maxMessage = 'This value is too long. It should have {{ limit }} character or less.{{ code }}|This value is too long. It should have {{ limit }} characters or less.{{ code }}';
    public $minMessage = 'This value is too short. It should have {{ limit }} character or more.{{ code }}|This value is too short. It should have {{ limit }} characters or more.{{ code }}';
    public $exactMessage = 'This value should have exactly {{ limit }} character.{{ code }}|This value should have exactly {{ limit }} characters.{{ code }}';
    public $charsetMessage = 'This value does not match the expected {{ charset }} charset.{{ code }}';

    public $errorCode           = E::LENGTH;
    public $minErrorCode        = E::LENGTH_MIN;
    public $maxErrorCode        = E::LENGTH_MAX;
    public $exactErrorCode      = E::LENGTH_EXACT;
    public $charsetErrorCode    = E::LENGTH_CHARSET;
}