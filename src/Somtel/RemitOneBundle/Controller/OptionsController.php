<?php

namespace Somtel\RemitOneBundle\Controller;

use AppBundle\Entity\Log;
use AppBundle\Entity\User;
use FOS\RestBundle\Controller\Annotations\Post;
use ITG\MillBundle;
use Somtel\RemitOneBundle\Form\ChangePasswordType;
use Somtel\RemitOneBundle\Form\editRemitterType;
use Somtel\RemitOneBundle\Form\ForgotPasswordType;
use Somtel\RemitOneBundle\Form\getDestinationCountriesType;
use Somtel\RemitOneBundle\Form\getProfileType;
use Somtel\RemitOneBundle\Form\LoginpinType;
use Somtel\RemitOneBundle\Form\LoginType;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Somtel\PipBundle;
use Somtel\PipBundle\Entity\PipCashinOrder;
use Somtel\RemitOneBundle\Payload\Status;
use Symfony\Component\HttpFoundation;
use FOS\RestBundle\Util;
use Symfony\Component\DependencyInjection;
use Symfony\Component\HttpFoundation\Request;


class OptionsController extends BaseController
{

    /**
     * Destination Countries Options
     *
     * @ApiDoc(
     *     section="Options",
     *     resource="DestinationCountry",
     *     input={
     *   "name"="",
     *   "class"="Somtel\RemitOneBundle\Form\getDestinationCountriesType"
     *  },
     * )
     *
     *
     * @Post("/options/destinations")
     *
     */

    public function postDestinationCountrysAction(Request $request)
    {
        $type = 'getDestinationCountries';
        $param = $request->request->all();
        $r1Service = $this->get('r1.remitter_service');
        $form = $this->createPostForm(getDestinationCountriesType::class);
        $form->handleRequest($request);

        if (!$form->isValid()) {
            return $this->show($form->getErrors(), null, 400);
        }

        $formData = $form->getData();
        $response = $r1Service->getDestinationCountries($formData);
        $this->log($param, $type, $response);

        return $this->show($response->getForClient(), null, 200);
    }



    /**
     * Source Countries Options
     *
     * @ApiDoc(
     *     section="Options",
     *     resource="SourceCountry"
     * )
     *
     * @Post("/options/sources")
     *
     */

    public function postSourceCountrysAction(Request $request)
    {
        $type = 'getSourceCountries';
        $param = $request->request->all();
        $r1Service = $this->get('r1.remitter_service');

        $form = $this->createPostForm(LoginpinType::class);
        $form->handleRequest($request);

        if (!$form->isValid()) {
            return $this->show($form->getErrors(), null, 400);
        }

        $formData = $form->getData();
        $response = $r1Service->getSourceCountries($formData);
        $this->log($param, $type, $response);

        return $this->show($response->getForClient(), null, 200);
    }

   
}
