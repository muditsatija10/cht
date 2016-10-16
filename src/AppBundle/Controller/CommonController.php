<?php

namespace AppBundle\Controller;
use AppBundle\Util\ErrorCodes;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class CommonController
{
    public function indexAction($name)
    {
        return new Response('<html><body>Hello '.$name.'!</body></html>');
    }
    
    public function apiResponseAction($response,$message='Success Response',$issuccess=true)
    {
        if(is_array($response)){
            $response=json_encode($response);
        }
        $arrayToReturn=array();
        if($issuccess){
            $arrayToReturn['status']="success";
            $arrayToReturn['message']=$message;
            $arrayToReturn['data']=[$response];
        }else{
            $arrayToReturn['status']="error";
            $arrayToReturn['message']=$message;
            $arrayToReturn['data']=[$response];
            
        }
        echo json_encode($arrayToReturn);exit;
    }
}