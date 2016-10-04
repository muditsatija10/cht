<?php

namespace Somtel\PipBundle\EventListener;

// use App\Services\Interfaces\ResponseLogger;
use Symfony\Component\HttpKernel\Event;
use JMS\Serializer\EventDispatcher\ObjectEvent;
use Somtel\PipBundle\Service;

class SerializationListener
{
    protected $utilService;

    public function __construct(Service\Util $utilService)
    {
        $this->utilService = $utilService;
    }

    public function onSerialize(ObjectEvent $event)
    {
        $visitor = $event->getVisitor();
        $entity = $event->getObject();

        $orderDocumentUrl = $this->utilService->getOrderDocumentUrl($entity->getBarcode());
        $visitor->addData('orderDocumentUrl', $orderDocumentUrl);

        return $visitor;
    }
}
