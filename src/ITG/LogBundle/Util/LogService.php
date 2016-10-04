<?php

namespace ITG\LogBundle\Util;

use AppBundle\Entity\Log;
use Doctrine\ORM\EntityManager;
use Symfony\Component\DependencyInjection\ContainerInterface;

class LogService
{
    private $em;
    private $container;
    private $project;
    
    public function __construct(EntityManager $em, ContainerInterface $container)
    {
        $this->em = $em;
        $this->container = $container;
        $this->project = $container->getParameter('itg_log')['project'];
    }
    
    public function info($request = null, $response = null, $payload = null, $extra = null, $project = null)
    {
        $this->log('INFO', $request, $response, $payload, $extra, $project);
    }
    
    public function error($request = null, $response = null, $payload = null, $extra = null, $project = null)
    {
        $this->log('ERROR', $request, $response, $payload, $extra, $project);
    }
    
    public function log($type = 'LOG', $request = null, $response = null, $payload = null, $extra = null, $project = null)
    {
        if($project == null)
        {
            $project = $this->project;
        }
        
        $log = new Log();
        $log
            ->setExtra($extra)
            ->setPayload($payload)
            ->setProject($project)
            ->setRequest($request)
            ->setResponse($response)
            ->setType($type)
            ;
        
        $this->em->persist($log);
        $this->em->flush($log);
        
        return $log;
    }
}