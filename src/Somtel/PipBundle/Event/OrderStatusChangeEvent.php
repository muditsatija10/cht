<?php

namespace Somtel\PipBundle\Event;

use Somtel\PipBundle\Entity;
use Symfony\Component\EventDispatcher\Event;

class OrderStatusChangeEvent extends Event
{
    private $order;

    // Order changed into this state.
    private $newStatus;

    public function __construct(Entity\PipCashinOrder $order, $newStatus)
    {
        $this->order = $order;
        $this->newStatus = $newStatus;
    }

    public function getOrder()
    {
        return $this->order;
    }

    public function getNewStatus()
    {
        return $this->newStatus;
    }
}
