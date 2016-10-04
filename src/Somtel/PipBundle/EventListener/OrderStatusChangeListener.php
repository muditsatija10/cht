<?php

namespace Somtel\PipBundle\EventListener;

use Symfony\Component\EventDispatcher\Event;
use Somtel\PipBundle\Service;
use Doctrine\ORM;

class OrderStatusChangeListener
{
    protected $entityManager;

    public function __construct(ORM\EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /*
     * When order statuses change on remote (pip-it.net)
     * we have to update our database and remitOne with that change.
     * This process is split in two parts:
     * 1) checking if status changed
     * 2) updating changed orders (both locally and on remitOne).
     *
     * This event listener takes care of second part.
     */
    public function onStatusChange(Event $event)
    {
        $order = $event->getOrder();
        $newStatus = $event->getNewStatus();
        $order->setStatus($newStatus);
        $this->entityManager->persist($order);
        $this->entityManager->flush();

        // @TODO: add pings to remitone here.
    }
}
