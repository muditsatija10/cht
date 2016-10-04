<?php

namespace ITG\MillBundle\Exception;

use Symfony\Component\HttpKernel\Exception\HttpException;

class VisibleException extends HttpException
{
    private $object;

    public function __construct($message = null, $errorCode = 999, $object = null, $code = 400)
    {
        $this->message = $message;
        $this->object = $object;

        if ($object != null)
        {
            $serializer = \JMS\Serializer\SerializerBuilder::create()->build();
            $parentMessage = $serializer->serialize([
                'message' => $message,
                'object' => $object
            ], 'json');
        }
        else
        {
            $parentMessage = json_encode(['message' => $message]);
        }

        parent::__construct($code, $parentMessage, null, array(), $errorCode);
    }

    public function getObject()
    {
        return $this->object;
    }
}