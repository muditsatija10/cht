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
			$ch = curl_init();

			$options = array(
			CURLOPT_CUSTOMREQUEST => "POST",
			CURLOPT_POSTFIELDS => array('login_id' => 'talkremit.api', 'api_key' => 'cdbeb4766fa4da19214ef1455c75f97d6e954c69f9e4f1af161f9cb0a66c5257'), 
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_SSL_VERIFYHOST => false,
			CURLOPT_SSL_VERIFYPEER => false,
			//  CURLOPT_HTTPHEADER  => $headers,
			CURLOPT_URL => $url
			);

			curl_setopt_array($ch , $options);

			$output = curl_exec($ch);

			if(!$output)
			{
			    echo "Curl Error : " . curl_error($ch);
			}
			else
			{
			   echo htmlentities($output);
			}
    	exit();
       
    }

}
