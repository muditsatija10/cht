<?php

namespace ITG\MillBundle\Controller;

use ITG\MillBundle\Exception\VisibleException;
use ITG\MillBundle\Util\ExceptionWrapperHandler;
use FOS\RestBundle\View\ExceptionWrapperHandlerInterface;
use FOS\RestBundle\View\ViewHandler;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Log\DebugLoggerInterface;

class ExceptionController extends \FOS\RestBundle\Controller\ExceptionController
{
    protected function getParameters(ViewHandler $viewHandler, $currentContent, $code, $exception,
        DebugLoggerInterface $logger = null, $format = 'html')
    {
        if ($exception->getClass() !== VisibleException::class)
        {
            return parent::getParameters($viewHandler, $currentContent, $code, $exception, $logger, $format);
        }

        $parameters = array(
            'status' => 'error',
            'status_code' => $code,
            'status_text' => array_key_exists($code, Response::$statusTexts) ? Response::$statusTexts[$code] : 'error',
            'currentContent' => $currentContent,
            'message' => $this->getExceptionMessage($exception),
            'exception' => $exception,
        );

        //dump($parameters);

        if ($viewHandler->isFormatTemplating($format))
        {
            $parameters['logger'] = $logger;
        }

        return $parameters;
    }

    protected function createExceptionWrapper(array $parameters)
    {
        /** @var ExceptionWrapperHandlerInterface $exceptionWrapperHandler */
        //$exceptionWrapperHandler = $this->container->get('fos_rest.exception_handler');
        $exceptionWrapperHandler = new ExceptionWrapperHandler();

        return $exceptionWrapperHandler->wrap($parameters);
    }
}