<?php

namespace ITG\CloudBundle\Controller;

use FOS\RestBundle\Controller\Annotations\Post;
use FOS\RestBundle\Controller\Annotations\Get;
use ITG\MillBundle\Controller\BaseController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;


class CloudController extends BaseController
{

	/**
	 * @ApiDoc()
     * @Post("/cloudFxRate")
     */

     public function postCloudFxRateAction(Request $request)
    {
            $loginId= $this->container->getParameter('api_login_id');
            $apiKey= $this->container->getParameter('api_key');
            $postData = $request->getContent(); 
            $requestArray  = json_decode($postData,true);
            $param=$requestArray;
            $url = "https://devapi.thecurrencycloud.com/v2/authenticate/api";
            $postArray = array('login_id' => $loginId, 'api_key' => $apiKey);
             
            $retunAuthVal = $this->initiateCrossDomainRequest($url, $postArray, 'POST', false, array());
            $retunAuthArray = json_decode($retunAuthVal,true);
             $auth_token = $retunAuthArray['auth_token'];
             $headers = array("X-Auth-Token: $auth_token");
             $fxPostArray  = $param;
             $fxAPIUrl = 'https://devapi.thecurrencycloud.com/v2/rates/detailed';
             $retunFxVal = $this->initiateCrossDomainRequest($fxAPIUrl, $fxPostArray, 'GET', true, $headers);
             $retunFxValArray=json_decode($retunFxVal);
             if(isset($retunFxValArray->currency_pair) && !empty($retunFxValArray->currency_pair)){
                    $this->forward('app.common_controller:apiResponseAction', array('response'=>$retunFxValArray));
                }else{
                    $this->forward('app.common_controller:apiResponseAction', array('response'=>'','message'=>$retunFxValArray,'issuccess'=>false));
                }
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
	 * @ApiDoc()
     * @Post("/validateBenificiary")
     */

     public function postValidateBenificiaryAction(Request $request)
    {
         $postData = $request->getContent(); 
        $requestArray  = json_decode($postData,true);
        $param=$requestArray;
         $currency=$param['currency'];
         $country=$param['country'];
         $benificiarytype=$param['benificiary_type'];
         $benificiaryArray=array('individual','company');
         if(!in_array($benificiarytype,$benificiaryArray)){
              $message=array('please provide a valid benificiary_type');
              $this->forward('app.common_controller:apiResponseAction', array('response'=>'','message'=>$message,'issuccess'=>false));
         }
         $mandetoryFeilds=$this->getMandeoryFeilds($currency,$country,$benificiarytype);
        echo $mandetoryFeilds;
        exit;
     }
     
      function getMandeoryFeilds($currency='USD',$country='US',$benificiarytype=NULL)
    {  
        $defaultArray = array('beneficiary_address', 'beneficiary_city', 'beneficiary_country', 'beneficiary_entity_type', 'beneficiary_first_name', 'beneficiary_last_name', 'beneficiary_state_or_province', 'beneficiary_postcode', 'payment_types', 'account_number');
        $loginId= $this->container->getParameter('api_login_id');
        $apiKey= $this->container->getParameter('api_key');
        $url = "https://devapi.thecurrencycloud.com/v2/authenticate/api";
        $postArray = array('login_id' => $loginId, 'api_key' => $apiKey);
        $retunAuthVal = $this->initiateCrossDomainRequest($url, $postArray, 'POST', false, array());
        $retunAuthArray = json_decode($retunAuthVal,true);
        $auth_token = $retunAuthArray['auth_token'];
        $headers = array("X-Auth-Token: $auth_token");
        $fxPostArray=array();
        $fxAPIUrl = 'https://devapi.thecurrencycloud.com/v2/reference/beneficiary_required_details?currency='.$currency.'&bank_account_country='.$country;
        $retunFxVal = $this->initiateCrossDomainRequest($fxAPIUrl, $fxPostArray, 'GET', true, $headers);
        $madetoryFeildArray=json_decode($retunFxVal);
        
        if(!empty($madetoryFeildArray->details) && !isset($madetoryFeildArray->error_code))
        {
            //$dataToSend=array();
            foreach($madetoryFeildArray->details as $key=>$val){
                //payment_type
                if($val->beneficiary_entity_type==$benificiarytype){
                    if(!isset($dataToSend[$val->payment_type]))
                    {
                        $array=(array) $val;
                        $array['payment_types']=$array['payment_type'];
                        unset($array['payment_type']);
                        $array['account_number']=$array['acct_number'];
                        unset($array['acct_number']);
                        //print_r($array);
                        $keysArray = array_keys($array);
                        $result=array_diff($keysArray,$defaultArray);
                        $resArray = array_values($result);
                        $dataToSend[$val->payment_type][]= implode(',', $resArray); 
                        //$dataToSend[$val->payment_type][]=  array_keys($array);
                    }else{
                        $array=(array) $val;
                        $array['payment_types']=$array['payment_type'];
                        unset($array['payment_type']);
                        $array['account_number']=$array['acct_number'];
                        unset($array['acct_number']);
                        $keysArray = array_keys($array);
                        $result=array_diff($keysArray,$defaultArray);
                        $resArray = array_values($result);
                        $dataToSend[$val->payment_type][]= implode(',', $resArray);
                        //$dataToSend[$val->payment_type][]=array_keys($array);
                    }
                }
                
            }
           
            //array_keys((array) $val)
        }
    
       if(isset($madetoryFeildArray->details) && !empty($madetoryFeildArray->details)){
           $responseArray = array("status" => "success", "message" => "Success Response", "data" => (object)$dataToSend);
           echo json_encode($responseArray);
           exit;
           //$this->forward('app.common_controller:apiResponseAction', array('response'=>(object)$dataToSend));
       }else{
           $this->forward('app.common_controller:apiResponseAction', array('response'=>'','message'=>$madetoryFeildArray,'issuccess'=>false));
       }
    }


    /**
	 * @ApiDoc()
     * @Post("/createConversion")
     */

     public function postCreateConversionAction(Request $request)
    {
            $loginId= $this->container->getParameter('api_login_id');
            $apiKey= $this->container->getParameter('api_key');
            //$param = $request->request->all();
            $postData = $request->getContent(); 
            $requestArray  = json_decode($postData,true);
            $param=$requestArray;
            $url = "https://devapi.thecurrencycloud.com/v2/authenticate/api";
            $postArray = array('login_id' => $loginId, 'api_key' => $apiKey);
             
            $retunAuthVal = $this->initiateCrossDomainRequest($url, $postArray, 'POST', false, array());
            $retunAuthArray = json_decode($retunAuthVal,true);
            $auth_token = $retunAuthArray['auth_token'];
            if(!empty($auth_token))
            {
                $headers = array("X-Auth-Token: $auth_token");
                #$fxPostArray  = array('buy_currency' => 'USD', 'sell_currency' =>'GBP', 'amount' => '100', 'fixed_side' => 'buy','reason'=>'Settling invoices','term_agreement'=>'true');
                $fxPostArray  = $param;
                $fxAPIUrl = 'https://devapi.thecurrencycloud.com/v2/conversions/create';
                $retunFxVal = $this->initiateCrossDomainRequest($fxAPIUrl, $fxPostArray, 'POST', true, $headers);
                $retunFxValArray=json_decode($retunFxVal);
                if(isset($retunFxValArray->id) && $retunFxValArray->id!=''){
                    $this->forward('app.common_controller:apiResponseAction', array('response'=>$retunFxValArray));
                }else{
                    $this->forward('app.common_controller:apiResponseAction', array('response'=>'','message'=>$retunFxValArray,'issuccess'=>false));
                }
            }

    }

     /**
     * @ApiDoc()
     * @Get("/cloudCurrency")
     */

    public function getCloudCurrencyAction(Request $request)
    {
            $loginId= $this->container->getParameter('api_login_id');
            $apiKey= $this->container->getParameter('api_key');
            $url = "https://devapi.thecurrencycloud.com/v2/authenticate/api";
            $postArray = array('login_id' => $loginId, 'api_key' => $apiKey);
            $retunAuthVal = $this->initiateCrossDomainRequest($url, $postArray, 'POST', false, array());
            $retunAuthArray = json_decode($retunAuthVal,true);
            if(!empty($retunAuthArray['auth_token']))
            {
                $auth_token = $retunAuthArray['auth_token'];
                $headers = array("X-Auth-Token: $auth_token");
                $fxAPIUrl = 'https://devapi.thecurrencycloud.com/v2/reference/currencies';
                $retunCurrency = $this->initiateCrossDomainRequest($fxAPIUrl, array(), 'GET', true, $headers);
                $retunCurrencyArray=json_decode($retunCurrency);
                if(isset($retunCurrencyArray->currencies) && !empty($retunCurrencyArray->currencies)){
                    $this->forward('app.common_controller:apiResponseAction', array('response'=>$retunCurrencyArray));
                }else{
                    $this->forward('app.common_controller:apiResponseAction', array('response'=>'','message'=>$retunCurrencyArray,'issuccess'=>false));
                }
            }

    }


     /**
     * @ApiDoc()
     * @Post("/cloudConversionDates")
     */

    public function getCloudConversionDatesAction(Request $request)
    {
            $loginId= $this->container->getParameter('api_login_id');
            $apiKey= $this->container->getParameter('api_key');
            $postData = $request->getContent(); 
            $requestArray  = json_decode($postData,true);
            $url = "https://devapi.thecurrencycloud.com/v2/authenticate/api";
            $postArray = array('login_id' => $loginId, 'api_key' => $apiKey);
            $retunAuthVal = $this->initiateCrossDomainRequest($url, $postArray, 'POST', false, array());
            $retunAuthArray = json_decode($retunAuthVal,true);
            if(!empty($retunAuthArray['auth_token']) && !empty($requestArray))
            {
              
                $auth_token = $retunAuthArray['auth_token'];
                $headers = array("X-Auth-Token: $auth_token");
                $APIUrl = 'https://devapi.thecurrencycloud.com/v2/reference/conversion_dates';
                $retunCurrencyDate = $this->initiateCrossDomainRequest($APIUrl, $requestArray, 'GET', true, $headers);
                $retunCurrencyDateArray=json_decode($retunCurrencyDate);
                if(isset($retunCurrencyDateArray->invalid_conversion_dates) && !empty($retunCurrencyDateArray->invalid_conversion_dates)){
                   $this->forward('app.common_controller:apiResponseAction', array('response'=>$retunCurrencyDateArray)); 
                }else{
                   $this->forward('app.common_controller:apiResponseAction', array('response'=>'','message'=>$retunCurrencyDateArray,'issuccess'=>false));
                }
            }
           

    }
    

     /**
     * @ApiDoc()
     * @Post("/cloudPaymentDates")
     */

    public function getCloudPaymentDatesAction(Request $request)
    {
            $loginId= $this->container->getParameter('api_login_id');
            $apiKey= $this->container->getParameter('api_key');
            $postData = $request->getContent(); 
            $requestArray  = json_decode($postData,true);
            $url = "https://devapi.thecurrencycloud.com/v2/authenticate/api";
            $postArray = array('login_id' => $loginId, 'api_key' => $apiKey);
            $retunAuthVal = $this->initiateCrossDomainRequest($url, $postArray, 'POST', false, array());
            $retunAuthArray = json_decode($retunAuthVal,true);
            if(!empty($retunAuthArray['auth_token']) && !empty($requestArray))
            {
                $auth_token = $retunAuthArray['auth_token'];
                $headers = array("X-Auth-Token: $auth_token");
                $APIUrl = 'https://devapi.thecurrencycloud.com/v2/reference/payment_dates';
                $retunCurrencyDate = $this->initiateCrossDomainRequest($APIUrl, $requestArray, 'GET', true, $headers);
                $retunCurrencyDateArray=json_decode($retunCurrencyDate);
                if(isset($retunCurrencyDateArray->invalid_payment_dates) && !empty($retunCurrencyDateArray->invalid_payment_dates)){
                   $this->forward('app.common_controller:apiResponseAction', array('response'=>$retunCurrencyDateArray)); 
                }else{
                   $this->forward('app.common_controller:apiResponseAction', array('response'=>'','message'=>$retunCurrencyDateArray,'issuccess'=>false));
                }
            }
           

    }



}
