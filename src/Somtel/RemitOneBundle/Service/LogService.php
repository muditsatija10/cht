<?php
/*
 * Currency service.
 * Gets info on currencies.
 */
namespace Somtel\RemitOneBundle\Service;

use AppBundle\Entity\Log;
use Doctrine\ORM\EntityManager;

use Somtel\RemitOneBundle\RemitOneBundle;

class LogService
{
    private $em;

    public function __construct(EntityManager $entityManager)
    {
        $this->em = $entityManager;
    }

    public function execute($request, $type, $response)
{
    $em = $this->em;
    $project = RemitOneBundle::class;

    $log = new Log();
    $log->setProject($project);
    $log->setRequest(json_encode($request));
    $log->setResponse(json_encode($response));
    $log->setType($type);
    $em->persist($log);
    $em->flush();
    return $log;
}
}
