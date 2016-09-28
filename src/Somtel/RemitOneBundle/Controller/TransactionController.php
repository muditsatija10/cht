<?php

namespace Somtel\RemitOneBundle\Controller;

use AppBundle\Entity\User;
use FOS\RestBundle\Controller\Annotations\Post;
use Appbundle\Entity\Log;
use ITG\MillBundle;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Somtel\PipBundle;
use Somtel\PipBundle\Entity\PipCashinOrder;
use Somtel\RemitOneBundle\Form\getChargesType;
use Symfony\Component\HttpFoundation;
use FOS\RestBundle\Util;
use Symfony\Component\DependencyInjection;
use Symfony\Component\HttpFoundation\Request;

class TransactionController extends BaseController
{


    /**
     * get Charge
     *
     * @ApiDoc(
     *     section="Transactions",
     *     resource="Transactions",
     *      input={
     *   "name"="",
     *   "class"="Somtel\RemitOneBundle\Form\getChargesType"
     *  },
     * )
     *
     * @Post("/getCharges")
     *
     */

    public function postGetchargesAction(Request $request)
    {
        $type = 'get-charges';
        $param = $request->request->all();

        $r1Service = $this->get('r1.remitter_service');

        $form = $this->createPostForm(getChargesType::class);
        $form->handleRequest($request);
        if (! $form->isValid()) {
            return $this->show($form->getErrors(), null, 400);
        }
        $formData = $form->getData();
        $response = $r1Service->getCharges($formData);
        $this->get('log')->execute($param, $type, $response);

        return $this->show($response->getForClient(), null, 200);
    }
}
