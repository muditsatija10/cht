<?php

namespace ITG\CloudBundle\Controller;

use FOS\RestBundle\Controller\Annotations\Post;
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
     * @Post("/cloudlogin")
     */

     public function postCloudloginAction(Request $request)
    {
			$url = "https://devapi.thecurrencycloud.com/v2/authenticate/api";
			$postArray = array('login_id' => 'talkremit.api', 'api_key' => 'dee68517cd4a23451a869df1d1df99cd17a2bd7352cab0ef55ba3008627e46ab');
             
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
	 * @ApiDoc()
     * @Post("/validateBenificiary")
     */

     public function postValidateBenificiaryAction(Request $request)
    {
         $param = $request->request->all();
         $currency=$param['currency'];
         $country=$param['country'];
         $mandetoryFeilds=$this->getMandeoryFeilds($currency,$country);
        echo $mandetoryFeilds;
        exit;
     }
     
      function getMandeoryFeilds($currency='USD',$country='US')
    {
        $url = "https://devapi.thecurrencycloud.com/v2/authenticate/api";
        $postArray = array('login_id' => 'talkremit.api', 'api_key' => 'dee68517cd4a23451a869df1d1df99cd17a2bd7352cab0ef55ba3008627e46ab');
        $retunAuthVal = $this->initiateCrossDomainRequest($url, $postArray, 'POST', false, array());
        $retunAuthArray = json_decode($retunAuthVal,true);
        $auth_token = $retunAuthArray['auth_token'];
        $headers = array("X-Auth-Token: $auth_token");
        $fxPostArray=array();
        $fxAPIUrl = 'https://devapi.thecurrencycloud.com/v2/reference/beneficiary_required_details?currency='.$currency.'&bank_account_country='.$country;
        $retunFxVal = $this->initiateCrossDomainRequest($fxAPIUrl, $fxPostArray, 'GET', true, $headers);
        $madetoryFeildArray=json_decode($retunFxVal);
        if(!empty($madetoryFeildArray))
        {
            $dataToSend=array();
            foreach($madetoryFeildArray->details as $key=>$val){
                //payment_type
                if(!isset($dataToSend[$val->payment_type]))
                {
                    //beneficiary_entity_type
                    if(!isset($dataToSend[$val->payment_type][$val->beneficiary_entity_type])){
                        $array=(array) $val;
                        $array['payment_types']=$array['payment_type'];
                        unset($array['payment_type']);
                        $array['account_number']=$array['acct_number'];
                        unset($array['acct_number']);
                        $dataToSend[$val->payment_type][$val->beneficiary_entity_type][]=  array_keys($array);
                    }else{
                        $array=(array) $val;
                        $array['payment_types']=$array['payment_type'];
                        unset($array['payment_type']);
                        $array['account_number']=$array['acct_number'];
                        unset($array['acct_number']);
                        $dataToSend[$val->payment_type][$val->beneficiary_entity_type][]=array_keys($array);
                    }
                }else{
                    $array=(array) $val;
                    $array['payment_types']=$array['payment_type'];
                    unset($array['payment_type']);
                    $array['account_number']=$array['acct_number'];
                    unset($array['acct_number']);
                    $dataToSend[$val->payment_type][$val->beneficiary_entity_type][]=array_keys($array);
                }
            }
            //array_keys((array) $val)
        }
        
       return json_encode($dataToSend);
    }


    /**
	 * @ApiDoc()
     * @Post("/createConversion")
     */

     public function postCreateConversionAction(Request $request)
    {
            $url = "https://devapi.thecurrencycloud.com/v2/authenticate/api";
			$postArray = array('login_id' => 'talkremit.api', 'api_key' => 'dee68517cd4a23451a869df1d1df99cd17a2bd7352cab0ef55ba3008627e46ab');
             
            $retunAuthVal = $this->initiateCrossDomainRequest($url, $postArray, 'POST', false, array());
            $retunAuthArray = json_decode($retunAuthVal,true);
            $auth_token = $retunAuthArray['auth_token'];
            if(!empty($auth_token))
            {
                $headers = array("X-Auth-Token: $auth_token");
                $fxPostArray  = array('buy_currency' => 'USD', 'sell_currency' =>'GBP', 'amount' => '100', 'fixed_side' => 'buy');
                
				$fxAPIUrl = 'https://devapi.thecurrencycloud.com/v2/conversions/create';
				$retunFxVal = $this->initiateCrossDomainRequest($fxAPIUrl, $fxPostArray, 'GET', true, $headers);
				echo $retunFxVal;
				exit(); 
            }

    }

}
