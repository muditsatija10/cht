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


class CloudController extends BaseController
{

	/**
     * @Post("/cloud")
     */

     public function postCloudAction(Request $request)
    {
    	  
    	echo $request->getContent();
         exit;
       
    }

}
