<?php

namespace Somtel\RemitOneBundle\Controller;

use AppBundle\Entity\Log;
use AppBundle\Entity\User;
use FOS\RestBundle\Controller\Annotations\Post;
use ITG\MillBundle;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Somtel\PipBundle;
use Somtel\PipBundle\Entity\PipCashinOrder;
use Somtel\RemitOneBundle\Form\createBeneficiaryType;
use Somtel\RemitOneBundle\Form\getBeneficiaryType;
use Somtel\RemitOneBundle\Form\listBeneficiariesType;
use Somtel\RemitOneBundle\Form\updateBeneficiaryType;
use Symfony\Component\HttpFoundation;
use FOS\RestBundle\Util;
use Symfony\Component\DependencyInjection;
use Symfony\Component\HttpFoundation\Request;

class BeneficiaryController extends BaseController
{

    /**
     * create new beneficiary
     *
     * @ApiDoc(
     *     section="Beneficiary",
     *     resource="Beneficiary",
     *        input={
     *   "name"="",
     *   "class"="Somtel\RemitOneBundle\Form\createBeneficiaryType"
     *  },
     * )
     *
     * @Post("/beneficiary")
     *
     */

    public function postBeneficiaryAction(Request $request)
    {
        $loginId= $this->container->getParameter('api_login_id');
        $apiKey= $this->container->getParameter('api_key');
        $type = 'create-eneficiary';
        $param = $request->request->all();
        
        $form = $this->createPostForm(createBeneficiaryType::class);
        $form->handleRequest($request);

        /*if(! $form->isValid()) {
            return $this->show($form->getErrors(), null, 400);
        }*/

        $formData = $form->getData();
        
        $r1Service = $this->get('r1.remitter_service');
        unset($formData['benef_fname']);
        unset($formData['benef_lname']);
        $formData['fname'] = $param['beneficiary_first_name'];
        $formData['lname'] = $param['beneficiary_last_name'];
        
        
        $url = "https://devapi.thecurrencycloud.com/v2/authenticate/api";
        $postArray = array('login_id' => $loginId, 'api_key' => $apiKey);
        $retunAuthVal = $this->initiateCrossDomainRequest($url, $postArray, 'POST', false, array());
        $retunAuthArray = json_decode($retunAuthVal,true);
        $auth_token = $retunAuthArray['auth_token'];
        
         $headers = array("X-Auth-Token: $auth_token");
         //$fxPostArray  = array('bank_account_holder_name' => $param['benef_name'], 'bank_country' =>'GB', 'currency' => 'GBP', 'name' => 'Employee Funds','account_number'=>$param['account_number'],'routing_code_type_1'=>'aba','routing_code_value_1'=>'011103093','routing_code_type_2'=>'bsb_code','routing_code_value_2'=>'088');
         
         
         $currencyCloudArray=$param;
         

         unset($currencyCloudArray['username']);
         unset($currencyCloudArray['session_token']);
         unset($currencyCloudArray['country_id']);
         unset($currencyCloudArray['dob']);
         unset($currencyCloudArray['telephone']);
         unset($currencyCloudArray['mobile']);
         unset($currencyCloudArray['email']);
         unset($currencyCloudArray['card_number']);
         unset($currencyCloudArray['bank']);
         unset($currencyCloudArray['bank_branch']);
         unset($currencyCloudArray['bank_branch_city']);
         unset($currencyCloudArray['Bank_branch_state']);
         unset($currencyCloudArray['bank_branch_telephone']);
         unset($currencyCloudArray['bank_branch_manager']);
         unset($currencyCloudArray['benef_bank_ifsc_code']);
         $currencyCloudArray['payment_types[]']=$param['payment_types'][0];
         unset($currencyCloudArray['payment_types']);
         
        
         
         $fxAPIUrl = 'https://devapi.thecurrencycloud.com/v2/beneficiaries/create';
         $retunFxVal = $this->initiateCrossDomainRequest($fxAPIUrl, $currencyCloudArray, 'POST', true, $headers);
        
          $fxPostArray=$param;
         $fxPostArray['payment_types[]']=$param['payment_types'][0];
         unset($fxPostArray['payment_types']);
         $fxPostArray['fname']= $fxPostArray['beneficiary_first_name'];
        $fxPostArray['lname']= $fxPostArray['beneficiary_last_name'];
        $fxPostArray['benef_name']= $fxPostArray['name'];
        $fxPostArray['address1']= $fxPostArray['beneficiary_address'];
        unset($fxPostArray['beneficiary_address']);
        $fxPostArray['city']= $fxPostArray['beneficiary_city'];
        unset($fxPostArray['beneficiary_city']);
        $fxPostArray['benef_bank_swift_code']= $fxPostArray['bic_swift'];
        unset($fxPostArray['bic_swift']);
        
        
        unset($fxPostArray['beneficiary_first_name']);
        unset($fxPostArray['beneficiary_last_name']);
        unset($fxPostArray['payment_types[]']);
        
        
        $response = $r1Service->createBeneficiary($fxPostArray);
        $this->get('log')->execute($param, $type, $response);
        return $this->show($response->getForClient(), null, 200);
    }
    
   
    
    
    public function postCloudloginAction(Request $request)
    {
            $loginId= $this->container->getParameter('api_login_id');
            $apiKey= $this->container->getParameter('api_key');
            $url = "https://devapi.thecurrencycloud.com/v2/authenticate/api";
            $postArray = array('login_id' => $loginId, 'api_key' => $apiKey);
             
            $retunAuthVal = $this->initiateCrossDomainRequest($url, $postArray, 'POST', false, array());
            $retunAuthArray = json_decode($retunAuthVal,true);
             $auth_token = $retunAuthArray['auth_token'];
             $headers = array("X-Auth-Token: $auth_token");
             $fxPostArray  = array('buy_currency' => 'USD', 'sell_currency' =>'GBP', 'amount' => '100', 'fixed_side' => 'buy');
             $fxAPIUrl = 'https://devapi.thecurrencycloud.com/v2/rates/detailed';
             $retunFxVal = $this->initiateCrossDomainRequest($fxAPIUrl, $fxPostArray, 'GET', true, $headers);
             echo $retunFxVal;
             exit(); 
    }


    private function initiateCrossDomainRequest($url, $postArray, $method, $flag, $header)
    {
     
            $ch = curl_init();
            if(!$flag){
					$options = array(
					CURLOPT_CUSTOMREQUEST => $method,
					CURLOPT_POSTFIELDS => $postArray, 
					CURLOPT_RETURNTRANSFER => true,
					CURLOPT_FOLLOWLOCATION => true,
					CURLOPT_SSL_VERIFYHOST => false,
					CURLOPT_SSL_VERIFYPEER => false,
					CURLOPT_URL => $url
					);
            	
            }
            else{

            	$options = array(
					CURLOPT_CUSTOMREQUEST => $method,
					CURLOPT_POSTFIELDS => $postArray, 
					CURLOPT_RETURNTRANSFER => true,
					CURLOPT_FOLLOWLOCATION => true,
					CURLOPT_SSL_VERIFYHOST => false,
					CURLOPT_SSL_VERIFYPEER => false,
					CURLOPT_HTTPHEADER  => $header,
					CURLOPT_URL => $url
					);
            }
			

			curl_setopt_array($ch , $options);

			$output = curl_exec($ch);

			if(!$output)
			{
			    echo "Curl Error : " . curl_error($ch);
			}
			else
			{
			  return $output;
			}
    	
    }

    /**
     * get one beneficiary
     *
     * @ApiDoc(
     *     section="Beneficiary",
     *     resource="Beneficiary",
     *        input={
     *   "name"="",
     *   "class"="Somtel\RemitOneBundle\Form\getBeneficiaryType"
     *  },
     * )
     *
     * @Post("/getbeneficiary")
     *
     */

    public function postGetbeneficiaryAction(Request $request)
    {
        $type = 'get-eneficiary';
        $param = $request->request->all();
        $form = $this->createPostForm(getBeneficiaryType::class);
        $form->handleRequest($request);

        if (! $form->isValid()) {
            return $this->show($form->getErrors(), null, 400);
        }

        $formData = $form->getData();
        $r1Service = $this->get('r1.remitter_service');
        $response = $r1Service->getBeneficiary($formData);
        $this->get('log')->execute($param, $type, $response);

        return $this->show($response->getForClient(), null, 200);
    }

    /**
     * get beneficiaries list
     *
     * @ApiDoc(
     *     section="Beneficiary",
     *     resource="Beneficiary",
     *        input={
     *   "name"="",
     *   "class"="Somtel\RemitOneBundle\Form\listBeneficiariesType"
     *  },
     * )
     *
     * @Post("/listbeneficiaries")
     *
     */

    public function postListbeneficiariesAction(Request $request)
    {
        $type = 'list-beneficiaries';
        $param = $request->request->all();
        $form = $this->createPostForm(listBeneficiariesType::class);
        $form->handleRequest($request);

        if (! $form->isValid()) {
            return $this->show($form->getErrors(), null, 400);
        }

        $formData = $form->getData();
        $r1Service = $this->get('r1.remitter_service');
        $response = $r1Service->listBeneficiaries($formData);
        $this->get('log')->execute($param, $type, $response);

        return $this->show($response->getForClient(), null, 200);
    }

    /**
     * get beneficiaries list
     *
     * @ApiDoc(
     *     section="Beneficiary",
     *     resource="Beneficiary",
     *        input={
     *   "name"="",
     *   "class"="Somtel\RemitOneBundle\Form\updateBeneficiaryType"
     *  },
     * )
     *
     * @Post("/updatebeneficiary")
     *
     */

    public function postUpdatebeneficiaryAction(Request $request)
    {
        $loginId= $this->container->getParameter('api_login_id');
        $apiKey= $this->container->getParameter('api_key');
        $type = 'update-beneficiary';
        $param = $request->request->all();
        $form = $this->createPostForm(updateBeneficiaryType::class);
        $form->handleRequest($request);
        $formData = $form->getData();
        
        $r1Service = $this->get('r1.remitter_service');
        unset($formData['benef_fname']);
        unset($formData['benef_lname']);
        $formData['fname'] = $param['beneficiary_first_name'];
        $formData['lname'] = $param['beneficiary_last_name'];
        
        
        $url = "https://devapi.thecurrencycloud.com/v2/authenticate/api";
        $postArray = array('login_id' => $loginId, 'api_key' => $apiKey);
        $retunAuthVal = $this->initiateCrossDomainRequest($url, $postArray, 'POST', false, array());
        $retunAuthArray = json_decode($retunAuthVal,true);
        $auth_token = $retunAuthArray['auth_token'];
        
         $headers = array("X-Auth-Token: $auth_token");
         //$fxPostArray  = array('bank_account_holder_name' => $param['benef_name'], 'bank_country' =>'GB', 'currency' => 'GBP', 'name' => 'Employee Funds','account_number'=>$param['account_number'],'routing_code_type_1'=>'aba','routing_code_value_1'=>'011103093','routing_code_type_2'=>'bsb_code','routing_code_value_2'=>'088');
         $currencyCloudArray=$param;
         

         unset($currencyCloudArray['username']);
         unset($currencyCloudArray['session_token']);
         unset($currencyCloudArray['country_id']);
         unset($currencyCloudArray['dob']);
         unset($currencyCloudArray['telephone']);
         unset($currencyCloudArray['mobile']);
         unset($currencyCloudArray['email']);
         unset($currencyCloudArray['card_number']);
         unset($currencyCloudArray['bank']);
         unset($currencyCloudArray['bank_branch']);
         unset($currencyCloudArray['bank_branch_city']);
         unset($currencyCloudArray['Bank_branch_state']);
         unset($currencyCloudArray['bank_branch_telephone']);
         unset($currencyCloudArray['bank_branch_manager']);
         unset($currencyCloudArray['benef_bank_ifsc_code']);
         $currencyCloudArray['payment_types[]']=$param['payment_types'][0];
         unset($currencyCloudArray['payment_types']);
         
         
         
         $fxAPIUrl = 'https://devapi.thecurrencycloud.com/v2/beneficiaries/create';
         $retunFxVal = $this->initiateCrossDomainRequest($fxAPIUrl, $currencyCloudArray, 'POST', true, $headers);
         
         $fxPostArray=$param;
         $fxPostArray['payment_types[]']=$param['payment_types'][0];
         unset($fxPostArray['payment_types']);
         
        $fxPostArray['fname']= $fxPostArray['beneficiary_first_name'];
        $fxPostArray['lname']= $fxPostArray['beneficiary_last_name'];
        $fxPostArray['benef_name']= $fxPostArray['name'];
        $fxPostArray['address1']= $fxPostArray['beneficiary_address'];
        unset($fxPostArray['beneficiary_address']);
        $fxPostArray['city']= $fxPostArray['beneficiary_city'];
        unset($fxPostArray['beneficiary_city']);
        $fxPostArray['benef_bank_swift_code']= $fxPostArray['bic_swift'];
        unset($fxPostArray['bic_swift']);
        
        
        unset($fxPostArray['beneficiary_first_name']);
        unset($fxPostArray['beneficiary_last_name']);
        unset($fxPostArray['payment_types[]']);
        
        
        $response = $r1Service->updateBeneficiary($fxPostArray);

        $this->get('log')->execute($param, $type, $response);

        return $this->show($response->getForClient(), null, 200);
    }

}
