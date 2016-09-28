<?php

namespace Somtel\RemitOneBundle\Controller;

use AppBundle\Entity\Log;
use AppBundle\Entity\User;
use FOS\RestBundle\Controller\Annotations\Post;
use ITG\MillBundle;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Somtel\PipBundle;
use Somtel\PipBundle\Entity\PipCashinOrder;
use Somtel\RemitOneBundle\Form\createBeneficiaryType;
use Somtel\RemitOneBundle\Form\getBeneficiaryType;
use Somtel\RemitOneBundle\Form\listBeneficiariesType;
use Somtel\RemitOneBundle\Form\updateBeneficiaryType;
use Symfony\Component\HttpFoundation;
use FOS\RestBundle\Util;
use Symfony\Component\DependencyInjection;
use Symfony\Component\HttpFoundation\Request;

class BeneficiaryController extends BaseController
{

    /**
     * create new beneficiary
     *
     * @ApiDoc(
     *     section="Beneficiary",
     *     resource="Beneficiary",
     *        input={
     *   "name"="",
     *   "class"="Somtel\RemitOneBundle\Form\createBeneficiaryType"
     *  },
     * )
     *
     * @Post("/beneficiary")
     *
     */

    public function postBeneficiaryAction(Request $request)
    {
        $type = 'create-eneficiary';
        $param = $request->request->all();
        $form = $this->createPostForm(createBeneficiaryType::class);
        $form->handleRequest($request);

        if(! $form->isValid()) {
            return $this->show($form->getErrors(), null, 400);
        }

        $formData = $form->getData();
        $r1Service = $this->get('r1.remitter_service');
        $response = $r1Service->createBeneficiary($formData);

        $this->get('log')->execute($param, $type, $response);

        return $this->show($response->getForClient(), null, 200);
    }

    /**
     * get one beneficiary
     *
     * @ApiDoc(
     *     section="Beneficiary",
     *     resource="Beneficiary",
     *        input={
     *   "name"="",
     *   "class"="Somtel\RemitOneBundle\Form\getBeneficiaryType"
     *  },
     * )
     *
     * @Post("/getbeneficiary")
     *
     */

    public function postGetbeneficiaryAction(Request $request)
    {
        $type = 'get-eneficiary';
        $param = $request->request->all();
        $form = $this->createPostForm(getBeneficiaryType::class);
        $form->handleRequest($request);

        if (! $form->isValid()) {
            return $this->show($form->getErrors(), null, 400);
        }

        $formData = $form->getData();
        $r1Service = $this->get('r1.remitter_service');
        $response = $r1Service->getBeneficiary($formData);
        $this->get('log')->execute($param, $type, $response);

        return $this->show($response->getForClient(), null, 200);
    }

    /**
     * get beneficiaries list
     *
     * @ApiDoc(
     *     section="Beneficiary",
     *     resource="Beneficiary",
     *        input={
     *   "name"="",
     *   "class"="Somtel\RemitOneBundle\Form\listBeneficiariesType"
     *  },
     * )
     *
     * @Post("/listbeneficiaries")
     *
     */

    public function postListbeneficiariesAction(Request $request)
    {
        $type = 'list-beneficiaries';
        $param = $request->request->all();
        $form = $this->createPostForm(listBeneficiariesType::class);
        $form->handleRequest($request);

        if (! $form->isValid()) {
            return $this->show($form->getErrors(), null, 400);
        }

        $formData = $form->getData();
        $r1Service = $this->get('r1.remitter_service');
        $response = $r1Service->listBeneficiaries($formData);
        $this->get('log')->execute($param, $type, $response);

        return $this->show($response->getForClient(), null, 200);
    }

    /**
     * get beneficiaries list
     *
     * @ApiDoc(
     *     section="Beneficiary",
     *     resource="Beneficiary",
     *        input={
     *   "name"="",
     *   "class"="Somtel\RemitOneBundle\Form\updateBeneficiaryType"
     *  },
     * )
     *
     * @Post("/updatebeneficiary")
     *
     */

    public function postUpdatebeneficiaryAction(Request $request)
    {
        $type = 'update-beneficiary';
        $param = $request->request->all();
        $form = $this->createPostForm(updateBeneficiaryType::class);
        $form->handleRequest($request);

        if (! $form->isValid()) {
            return $this->show($form->getErrors(), null, 400);
        }

        $formData = $form->getData();
        $r1Service = $this->get('r1.remitter_service');
        $response = $r1Service->updateBeneficiary($formData);

        $this->get('log')->execute($param, $type, $response);

        return $this->show($response->getForClient(), null, 200);
    }

}
