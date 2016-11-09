<?php

namespace AppBundle\Util;

use AppBundle\Entity\Log;
use AppBundle\Entity\W2Check;
use AppBundle\Entity\W2CheckBeneficiary;
use Doctrine\ORM\EntityManager;
use SoapClient;
use Symfony\Component\HttpFoundation\Response;


class SisPlusRequest
{
    private $_em;

    public function __construct(EntityManager $entityManager)
    {
        $this->_em = $entityManager;
    }

//TODO: Fix copied code

    public function execute($request,$is_remitter)
    {

        $validate = $request;
        
        $em = $this->_em;
        if($is_remitter == true)
        {
        $user = $em->getRepository('AppBundle:W2Check')->findOneBy([
            'user' => $validate['id']],['id' => 'DESC']);
        }else
        {
        $user = $em->getRepository('AppBundle:W2CheckBeneficiary')->findOneBy([
            'user' => $validate['id']],['id' => 'DESC']);
        }
        if(!empty($user))
        {
            
            if($user->getValid()=='Pass'){

                $currentDate = new \DateTime();
                $currentDate->modify('-3 months');

                if($currentDate >= $user->getTimeStamp())
                {
                    return $this->Validate($validate,$is_remitter);
                }else
                {
                    return $user;
                }

            }else {
                if($user['status'] !== 'blocked')
                {
                    return $this->Validate($validate,$is_remitter); 
                }
                return $user;
            }

        }else
        {

            return $this->Validate($validate,$is_remitter);
        }
        

    }

    function Validate($validate,$is_remitter)
    {

        $em = $this->_em;
        $log = new Log();
        $log->setProject('w2');

        $url= 'https://apiv3-uat.w2globaldata.com/Service.svc?wsdl';

        $client = new SoapClient($url);
if($is_remitter == false)
{
    $validate['firstname'] = $validate['fname'];
    $validate['lastname'] = $validate['lname'];
}
        $string =array(
            'serviceRequest' => array
            (
                'BundleData'=> array
                (
                    'BundleName' =>'KYC_SIS_PEP'
                ),
                'QueryData'=> array
                (
                    'NameQuery' =>$validate['firstname'].' '.$validate['lastname']
                ),
                'ServiceAuthorisation'=> array
                (
                    'APIKey' =>'2ef54e12-0c3c-499b-afc3-311dc8776d0f',
                    'ClientReference' => 'Testing W2 services'
                )
            )
        );
  
        $log->setPayload(json_encode($string));
        $response = $client->KYCCheck($string);
       
        $log->setResponse(json_encode($response));
        $log->setType('w2_check');
       
        /* Print webservice response */
        $service_Transactions = $response->KYCCheckResult->ProcessRequestResult->TransactionInformation->ServiceTransactions->ServiceTransactionInformation;
        $em->persist($log);
        $valid = null;
        $notValid = null;

        foreach ($service_Transactions as $transaction)
        {

            if($transaction->ValidationResult == 'Pass')
            {
                $valid = 'Pass';
            }else
            {
                $notValid = 'blocked';
            }
        }

        if($is_remitter == true)
        {
        $user = new W2Check();
        }else
        {
        $user = new W2CheckBeneficiary(); 
        }
        $user->setUser($validate['id']);
        if(empty($notValid)){
            $user->setValid($valid);
        }else
        {
            $user->setValid($notValid);
        }

        $em->persist($user);
        $em->flush();
        Return $user;
    }

}
