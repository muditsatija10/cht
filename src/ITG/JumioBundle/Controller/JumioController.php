<?php

namespace ITG\JumioBundle\Controller;

use FOS\RestBundle\Controller\Annotations\Post;
use ITG\JumioBundle\Util\NetverifyObject;
use ITG\MillBundle\Controller\BaseController;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpFoundation\File\File;

class JumioController extends BaseController
{
    /**
     * @ApiDoc()
     */
    public function getJumioAction()
    {
        
        return $this->show('');
    }

    /**
     * Callback for Jumio response
     * 
     * @ApiDoc()
     * 
     * @Post("/callback")
     */
    public function postCallbackAction()
    {
        // TODO: check if this comes from jumio IPs, otherwise this may be faked

        $param = $request->request->all();
        echo json_encode($param);
        exit();
    }
}
