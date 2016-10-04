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


class DahabController extends BaseController
{

	/**
     * @Post("/customer")
     */

     public function postCustomerAction(Request $request)
    {
    	  
    	$postData = $request->getContent();
        $postDataArray  = json_decode($postData);

        $url = "https://www.dahabonline.co.uk/talkRemit/Validate/Customer?HASH=0ef0c5a96950d1e8ce0d0f173c152291bb722778cbddb6efe7d9b86b5e0acaae&API_KEY=21f3ab93-b5f9-4118-968c-6a45f551e1e6";
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
