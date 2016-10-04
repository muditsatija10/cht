<?php
/*
 * Merchant facade.
 * Here should be all the business logic related to merchants.pip-it.net.
 */

namespace Somtel\PipBundle\Service;

use GuzzleHttp;
use Somtel\PipBundle\Entity;
use Somtel\PipBundle\Event;
use Symfony\Component\Serializer;
use Doctrine\ORM;

class CashinFacade
{
    /*
     * Http client of some sorts.
     * In this case - httpguzle.
     */
    protected $transporter;


    protected $entityManager;


    /*
     * Utility service.
     */
    protected $util;

    /*
     * Event dispatcher.
     */
    protected $dispatcher;

    public function __construct(
        $transporter,
        ORM\EntityManagerInterface $entityManager,
        $util,
        $dispatcher
    ) {
        $this->transporter = $transporter;
        $this->entityManager = $entityManager;
        $this->util = $util;
        $this->dispatcher = $dispatcher;

        $normalizer = new Serializer\Normalizer\GetSetMethodNormalizer();
        $this->normalizer = $normalizer;
    }


    /*
     * Order creation consists of data normalization, posting to remote service and saving order to local db.
     * This method does all that (using separate services).
     */
    public function createOrder($orderData, $user)
    {
        $preparedOrder = $this->util->prepareOrder($orderData);
        $createdOrder = $this->transporter->createOrder($preparedOrder);
        if ($createdOrder !== false) {
             $orderEntity = $this->normalizer->denormalize($createdOrder, 'Somtel\PipBundle\Entity\PipCashinOrder');
             $orderEntity->setUser($user);

              $this->entityManager->persist($orderEntity);
              $this->entityManager->flush();

              return $orderEntity;
        }
        return $createdOrder;
    }


    /*
     * When order statuses change on remote (pip-it.net)
     * we have to update our database and remitOne with that change.
     * This process is split in two parts:
     * 1) checking if status changed
     * 2) updating changed orders (both locally and on remitOne).
     *
     * This method takes care of first part (checking for status changes).
     * Fires an event if order status changed.
     */
    public function checkForStatusChanges()
    {
        $localOrders = $this->entityManager->getRepository('SomtelPipBundle:PipCashinOrder')
                     ->findByStatus(Entity\PipCashinOrder::STATUS_PENDING);

        foreach ($localOrders as $localOrder) {
            $remoteOrder = $this->transporter->getOrder($localOrder->getBarcode());

            // @TODO: log when remoteOrder is empty.
            if (!empty($remoteOrder) && ($remoteOrder["status"] !== $localOrder->getStatus())) {
                $event = new Event\OrderStatusChangeEvent($localOrder, $remoteOrder["status"]);
                $this->dispatcher->dispatch('pip.status_changed', $event);
            }
        }
    }


    public function getLastErrors()
    {
        return $this->transporter->getLastErrors();
    }
}
