<?php

namespace Somtel\PipBundle\EventListener;

// use App\Services\Interfaces\ResponseLogger;
use Symfony\Component\HttpKernel\Event;

class ResponseListener
{
    protected $logger;
    protected $user;

    public function __construct($responseLogger)
    {
        $this->logger = $responseLogger;
    }

    public function onKernelResponse(Event\FilterResponseEvent $event)
    {
        if (! $this->shouldLog($event)) {
            return;
        }
        $this->logger->log(
            'REQUEST',
            $event->getRequest(),
            $event->getResponse()
        );
    }

    public function onKernelException(Event\GetResponseForExceptionEvent $event)
    {
        if (! $this->shouldLog($event)) {
            return;
        }
        $exception = $event->getException();
        $this->logger->error(
            $event->getRequest(),
            $event->getResponse(),
            $exception->getMessage(),
            $exception->getTraceAsString()
        );
    }

    public function shouldLog($event)
    {
        $request = $event->getRequest();
        $needle = '/pip/';
        return $event->isMasterRequest() && strpos($request->getUri(), $needle) > -1;
    }
}
