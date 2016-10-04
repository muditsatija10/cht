<?php

namespace ITG\JumioBundle\EventListener;

use Doctrine\ORM\EntityManager;
use ITG\JumioBundle\Event\NetverifyRequestChangeEvent;
use ITG\JumioBundle\Util\JumioService;
use ITG\JumioBundle\Util\NetverifyObject;
use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\HttpFoundation\File\File;

class NetverifyRequestChangeListener
{
    private $svc;
    private $rootDir;
    private $em;

    public function __construct(JumioService $svc, $rootDir, EntityManager $em)
    {
        $this->svc = $svc;
        $this->rootDir = "$rootDir/../";
        $this->em = $em;
    }

    public function onChange(NetverifyRequestChangeEvent $event)
    {
        $entity = $event->getNetverify();

        // If everything we need is set, then send everything to Jumio
        if($entity->getPhotoFront() && $entity->getPhotoBack() && $entity->getPhotoFace())
        {
            $svc = $this->svc;

            // TODO: handle file not exists errors
            $front = new File($this->rootDir . $entity->getPhotoFront());
            $back = new File($this->rootDir . $entity->getPhotoBack());
            $face = new File($this->rootDir . $entity->getPhotoFace());

            $obj = new NetverifyObject();
            $obj
                ->setMerchantIdScanReference($entity->getUserReference())
                ->setFrontSideImage($front)
                ->setBackSideImage($back)
                ->setFaceImage($face)
            ;

            $res = $svc->performNetverify($obj);
            
            $resCode = $res->getStatusCode();
            
            // If success
            if($resCode >= 200 && $resCode < 300)
            {
                $entity->setSent(new \DateTime());
                $this->em->flush();
            }
        }
    }
}