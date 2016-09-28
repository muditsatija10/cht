<?php

namespace AppBundle\Util;

use AppBundle\Entity\Log;
use Doctrine\ORM\EntityManager;


class SisPlusRequest
{
    private $_em;

    public function __construct(EntityManager $entityManager)
    {
        $this->_em = $entityManager;
    }

//TODO: Fix copied code

    public function execute($request)
    {
        Die(print_r('test'));
        $response = $this->createBeneficiary($request); //calls the Api class for response
        $log = new Log();

        $log->setRequest(json_encode($request));
        $log->setResponse(json_encode($response));
        $log->setType('Beneficiaries');
        $em = $this->_em;
        $em->persist($log);
        $em->flush();

        //TODO: Return some status to the Create Beneficiary controller
        return $response; // response can be anything
    }

}
