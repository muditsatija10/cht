<?php

namespace Somtel\RemitOneBundle\Controller;

use Somtel\RemitOneBundle;
use AppBundle\Entity\Log;
use AppBundle\Entity\User;
use FOS\RestBundle\Controller\Annotations\Post;
use ITG\MillBundle;
use Somtel\RemitOneBundle\Form\ChangePasswordType;
use Somtel\RemitOneBundle\Form\editRemitterType;
use Somtel\RemitOneBundle\Form\ForgotPasswordType;
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

class BaseController extends MillBundle\Controller\BaseController
{
    public function log($request, $type, $response)
    {
        $em = $this->getDoctrine()->getManager();
        $project = RemitOneBundle::class;

        $log = new Log();
        $log->setProject($project);
        $log->setRequest(json_encode($request));
        $log->setResponse(json_encode($response));
        $log->setType($type);
        $em->persist($log);
        $em->flush();
    }
}
