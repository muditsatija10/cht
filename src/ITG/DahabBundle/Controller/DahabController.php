<?php

namespace ITG\DahabBundle\Controller;

use FOS\RestBundle\Controller\Annotations\Post;
use ITG\MillBundle\Controller\BaseController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;


class DahabController extends BaseController
{

	/**
	 * @ApiDoc()
     * @Post("/validateCustomer")
     */

     public function postValidateCustomerAction(Request $request)
    {
    	  
    	 $postData = $request->getContent();
         $apiKey = '222be339-9c09-45d6-be77-03c8b3e0d7c9';
         $hashString = $postData.$apiKey;
         $hash = hash("sha256", $hashString);
        $url = "https://www.dahabonline.co.uk/talkRemit/Validate/Customer?HASH=".$hash."&API_KEY=21f3ab93-b5f9-4118-968c-6a45f551e1e6";
		$cert_file = '/var/www/html/somtel/file.crt.pem';
		$cert_key = '/var/www/html/somtel/file.key.pem';
		$cert_password = '#$SGADS9XGH927T&$%$';
		$headers = array('Content-Type: application/json');
		$data_string = $postData; 
		$ch = curl_init();
		 
		$options = array(
		    CURLOPT_CUSTOMREQUEST => "POST",
		    CURLOPT_POSTFIELDS => $data_string, 
		    CURLOPT_RETURNTRANSFER => true,
		    CURLOPT_FOLLOWLOCATION => true,
		    CURLOPT_SSL_VERIFYHOST => false,
		    CURLOPT_SSL_VERIFYPEER => false,
		    CURLOPT_HTTPHEADER  => $headers,
		    CURLOPT_URL => $url ,
		    CURLOPT_SSLCERT => $cert_file ,
		    CURLOPT_SSLKEY => $cert_key,
		    CURLOPT_SSLCERTPASSWD => $cert_password,
		    CURLOPT_SSLKEYPASSWD => $cert_password
		);
		 
		curl_setopt_array($ch , $options);
		 
		$output = curl_exec($ch);
		 
		if(!$output)
		{
		    echo "Curl Error : " . curl_error($ch);
		}
		else
		{
		    echo $output;
		}
        exit();
       
    }


    /**
	 * @ApiDoc()
     * @Post("/processPayment")
     */

     public function postProcessPaymentAction(Request $request)
    {

         $postData = $request->getContent();
         $apiSecret = '222be339-9c09-45d6-be77-03c8b3e0d7c9';
         $hashString = $postData.$apiSecret;
         $hash = hash("sha256", $hashString);
         $url = "https://www.dahabonline.co.uk/talkRemit/Process/Payment?HASH=".$hash."&API_KEY=21f3ab93-b5f9-4118-968c-6a45f551e1e6";
		$cert_file = '/var/www/html/somtel/file.crt.pem';
		$cert_key = '/var/www/html/somtel/file.key.pem';
		$cert_password = '#$SGADS9XGH927T&$%$';
		$headers = array('Content-Type: application/json');
		$data_string = $postData; 
		$ch = curl_init();
		 
		$options = array(
		    CURLOPT_CUSTOMREQUEST => "POST",
		    CURLOPT_POSTFIELDS => $data_string, 
		    CURLOPT_RETURNTRANSFER => true,
		    CURLOPT_FOLLOWLOCATION => true,
		    CURLOPT_SSL_VERIFYHOST => false,
		    CURLOPT_SSL_VERIFYPEER => false,
		    CURLOPT_HTTPHEADER  => $headers,
		    CURLOPT_URL => $url ,
		    CURLOPT_SSLCERT => $cert_file ,
		    CURLOPT_SSLKEY => $cert_key,
		    CURLOPT_SSLCERTPASSWD => $cert_password,
		    CURLOPT_SSLKEYPASSWD => $cert_password
		);
		 
		curl_setopt_array($ch , $options);
		 
		$output = curl_exec($ch);
		 
		if(!$output)
		{
		    echo "Curl Error : " . curl_error($ch);
		}
		else
		{
		    echo $output;
		}
        exit();


    }


     /**
	 * @ApiDoc()
     * @Post("/sendSMS")
     */

     public function postSendSMSAction(Request $request)
    {

         $postData = $request->getContent();
         $apiSecret = '222be339-9c09-45d6-be77-03c8b3e0d7c9';
         $hashString = $postData.$apiSecret;
         $hash = hash("sha256", $hashString);
         $url = "https://www.dahabonline.co.uk/talkRemit/Send/SMS?HASH=".$hash."&API_KEY=21f3ab93-b5f9-4118-968c-6a45f551e1e6";
		$cert_file = '/var/www/html/somtel/file.crt.pem';
		$cert_key = '/var/www/html/somtel/file.key.pem';
		$cert_password = '#$SGADS9XGH927T&$%$';
		$headers = array('Content-Type: application/json');
		$data_string = $postData; 
		$ch = curl_init();
		 
		$options = array(
		    CURLOPT_CUSTOMREQUEST => "POST",
		    CURLOPT_POSTFIELDS => $data_string, 
		    CURLOPT_RETURNTRANSFER => true,
		    CURLOPT_FOLLOWLOCATION => true,
		    CURLOPT_SSL_VERIFYHOST => false,
		    CURLOPT_SSL_VERIFYPEER => false,
		    CURLOPT_HTTPHEADER  => $headers,
		    CURLOPT_URL => $url ,
		    CURLOPT_SSLCERT => $cert_file ,
		    CURLOPT_SSLKEY => $cert_key,
		    CURLOPT_SSLCERTPASSWD => $cert_password,
		    CURLOPT_SSLKEYPASSWD => $cert_password
		);
		 
		curl_setopt_array($ch , $options);
		 
		$output = curl_exec($ch);
		 
		if(!$output)
		{
		    echo "Curl Error : " . curl_error($ch);
		}
		else
		{
		    echo $output;
		}
        exit();

    }

}
