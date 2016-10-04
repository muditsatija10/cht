<?php

namespace ITG\MillBundle\Util;

use ITG\MillBundle\Exception\VisibleException;
use Symfony\Component\Form\FormInterface;

class ExceptionWrapper
{
    private $code;
    private $message;
    private $errors;
    private $errorCode;
    private $object;

    /**
     * @param array $data
     */
    public function __construct($data)
    {
        $this->code = $data['status_code'];
        if(isset($data['exception']))
        {
            if ($c = $data['exception']->getCode())
            {
                $this->errorCode = $c;
            }

            if ($data['exception']->getClass() === VisibleException::class)
            {
                $obj = json_decode($data['message'], true);
                if (isset($obj['message']))
                {
                    $this->message = $obj['message'];
                }

                if (isset($obj['object']))
                {
                    $this->object = $obj['object'];
                }
            }
            else
            {
                $this->message = $data['message'];
            }
        }
        else
        {
            $this->message = $data['message'];
        }

        if (isset($data['errors']))
        {
            $this->errors = $data['errors'];
        }
    }

    /**
     * @return int
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @return FormInterface
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @return int
     */
    public function getErrorCode()
    {
        return $this->errorCode;
    }

    /**
     * @return mixed
     */
    public function getObject()
    {
        return $this->object;
    }
}