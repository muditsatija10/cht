<?php

namespace ITG\MillBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class NotBlankValidator extends ConstraintValidator
{
    /**
     * {@inheritdoc}
     */
    public function validate($value, Constraint $constraint)
    {
        if (!$constraint instanceof NotBlank) {
            throw new UnexpectedTypeException($constraint, __NAMESPACE__.'\NotBlank');
        }

        if (false === $value || (empty($value) && '0' != $value)) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ value }}', $this->formatValue($value))
                ->setParameter('{{ code }}', '|' . $constraint->errorCode)
                ->setCode(NotBlank::IS_BLANK_ERROR)
                //->setErrorCode($constraint->errorCode)
                ->addViolation();
        }
    }
}