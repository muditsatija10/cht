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


class PaymentCloudController extends BaseController
{

        /**
         * 
         * @ApiDoc(
     *     section="Cloud Payment",
     *     resource="payment",
     *     
     * )
     */
    public function getFindPaymentAction(Request $request)
    {
        $loginId= $this->container->getParameter('api_login_id');
        $apiKey= $this->container->getParameter('api_key');
        $url = "https://devapi.thecurrencycloud.com/v2/authenticate/api";
	$postArray = array('login_id' => $loginId, 'api_key' => $apiKey);
        $retunAuthVal = $this->initiateCrossDomainRequest($url, $postArray, 'POST', false, array());
        $retunAuthArray = json_decode($retunAuthVal,true);
        $auth_token = $retunAuthArray['auth_token'];
        $headers = array("X-Auth-Token: $auth_token");
        //$fxPostArray  = array('buy_currency' => 'USD', 'sell_currency' =>'GBP', 'amount' => '100', 'fixed_side' => 'buy');
        $fxPostArray=array();
        $fxAPIUrl = 'https://devapi.thecurrencycloud.com/v2/payments/find';
        $retunFxVal = $this->initiateCrossDomainRequest($fxAPIUrl, $fxPostArray, 'GET', true, $headers);
        $retunFxValArray=json_decode($retunFxVal);
        
        if(isset($retunFxValArray->payments)){
            $this->forward('app.common_controller:apiResponseAction', array('response'=>$retunFxValArray));
        }else{
            $this->forward('app.common_controller:apiResponseAction', array('response'=>'','message'=>$retunFxValArray,'issuccess'=>false));
        }
        
    }
    
        /**
         * 
         * @ApiDoc(
     *     section="Cloud Payment",
     *     resource="payment",
     *     
     * )
     */
    public function postCreatePaymentAction(Request $request)
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
        $headers = array("X-Auth-Token: $auth_token");
        
        $fxPostArray  = $param;
        $fxAPIUrl = 'https://devapi.thecurrencycloud.com/v2/payments/create';
        $retunFxVal = $this->initiateCrossDomainRequest($fxAPIUrl, $fxPostArray, 'POST', true, $headers);
        $retunFxValArray=json_decode($retunFxVal);
        if(isset($retunFxValArray->id) && $retunFxValArray->id!=''){
            $this->forward('app.common_controller:apiResponseAction', array('response'=>$retunFxValArray));
        }else{
            $this->forward('app.common_controller:apiResponseAction', array('response'=>'','message'=>$retunFxValArray,'issuccess'=>false));
        }
        
    }
    
    
      /**
         * 
         * @ApiDoc(
     *     section="Cloud Payment",
     *     resource="payment",
     *     
     * )
     */
    public function postRetrivePaymentSubmissionAction(Request $request)
    {
        $loginId= $this->container->getParameter('api_login_id');
        $apiKey= $this->container->getParameter('api_key');
        $postData = $request->getContent(); 
        $requestArray  = json_decode($postData,true);
        $param=$requestArray;
        $id=$param['id'];
        $url = "https://devapi.thecurrencycloud.com/v2/authenticate/api";
	$postArray = array('login_id' => $loginId, 'api_key' => $apiKey);
        $retunAuthVal = $this->initiateCrossDomainRequest($url, $postArray, 'POST', false, array());
        $retunAuthArray = json_decode($retunAuthVal,true);
        $auth_token = $retunAuthArray['auth_token'];
        $headers = array("X-Auth-Token: $auth_token");
        
        $fxPostArray  = $param;
        $fxAPIUrl = 'https://devapi.thecurrencycloud.com/v2/payments/'.$id.'/submission';
        
        $retunFxVal = $this->initiateCrossDomainRequest($fxAPIUrl, $fxPostArray, 'GET', true, $headers);
        $retunFxValArray=json_decode($retunFxVal);
        if(array_key_exists("mt103",$retunFxValArray)){
            $this->forward('app.common_controller:apiResponseAction', array('response'=>$retunFxValArray));
        }else{
            $this->forward('app.common_controller:apiResponseAction', array('response'=>'','message'=>$retunFxValArray,'issuccess'=>false));
        }
       
    }
    
      /**
         * 
         * @ApiDoc(
     *     section="Cloud Payment",
     *     resource="payment",
     *     
     * )
     */
    public function postRetrivePaymentDetailAction(Request $request)
    {
        $loginId= $this->container->getParameter('api_login_id');
        $apiKey= $this->container->getParameter('api_key');
        //$param = $request->request->all();
        $postData = $request->getContent(); 
        $requestArray  = json_decode($postData,true);
        $param=$requestArray;
        $id=$param['id'];
        $url = "https://devapi.thecurrencycloud.com/v2/authenticate/api";
	$postArray = array('login_id' => $loginId, 'api_key' => $apiKey);
        $retunAuthVal = $this->initiateCrossDomainRequest($url, $postArray, 'POST', false, array());
        $retunAuthArray = json_decode($retunAuthVal,true);
        $auth_token = $retunAuthArray['auth_token'];
        $headers = array("X-Auth-Token: $auth_token");
        
        $fxPostArray  = $param;
        $fxAPIUrl = 'https://devapi.thecurrencycloud.com/v2/payments/'.$id;
        
        $retunFxVal = $this->initiateCrossDomainRequest($fxAPIUrl, $fxPostArray, 'GET', true, $headers);
        $retunFxValArray=json_decode($retunFxVal);
        if(isset($retunFxValArray->id)){
            $this->forward('app.common_controller:apiResponseAction', array('response'=>$retunFxValArray));
        }else{
            $this->forward('app.common_controller:apiResponseAction', array('response'=>'','message'=>$retunFxValArray,'issuccess'=>false));
        }
    }
    
    
      /**
         * 
         * @ApiDoc(
     *     section="Cloud Payment",
     *     resource="payment",
     *     
     * )
     */
    public function postDeletePaymentAction(Request $request)
    {
        $loginId= $this->container->getParameter('api_login_id');
        $apiKey= $this->container->getParameter('api_key');
        $postData = $request->getContent(); 
        $requestArray  = json_decode($postData,true);
        $param=$requestArray;
        $id=$param['id'];
        $url = "https://devapi.thecurrencycloud.com/v2/authenticate/api";
	$postArray = array('login_id' => $loginId, 'api_key' => $apiKey);
        $retunAuthVal = $this->initiateCrossDomainRequest($url, $postArray, 'POST', false, array());
        $retunAuthArray = json_decode($retunAuthVal,true);
        $auth_token = $retunAuthArray['auth_token'];
        $headers = array("X-Auth-Token: $auth_token");
        
        $fxPostArray  = array();
        $fxAPIUrl = 'https://devapi.thecurrencycloud.com/v2/payments/'.$id.'/delete';
        
        $retunFxVal = $this->initiateCrossDomainRequest($fxAPIUrl, $fxPostArray, 'POST', true, $headers);
        $retunFxValArray=json_decode($retunFxVal);
        if(isset($retunFxValArray->id)){
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

     
}
