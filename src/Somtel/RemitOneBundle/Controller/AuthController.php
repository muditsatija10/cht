<?php

namespace Somtel\RemitOneBundle\Controller;

use AppBundle\Entity\Log;
use AppBundle\Entity\User;
use FOS\RestBundle\Controller\Annotations\Post;
use ITG\MillBundle;
use ITG\MillBundle\Controller\BaseController;
use Somtel\RemitOneBundle\Form\changePasswordType;
use Somtel\RemitOneBundle\Form\confirmRegisterType;
use Somtel\RemitOneBundle\Form\createRemitterType;
use Somtel\RemitOneBundle\Form\editRemitterType;
use Somtel\RemitOneBundle\Form\forgotPasswordType;
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

class AuthController extends BaseController
{

    /**
     * Remmiter Login
     *
     * @ApiDoc(
     *     section="Remitter",
     *     resource="Remitters",
     *     input={
     *   "name"="",
     *   "class"="Somtel\RemitOneBundle\Form\LoginType"
     *  },
     * )
     *
     *
     * @Post("/remitter/login")
     *
     */

    public function postRemitterloginAction(Request $request)
    {
        $type = 'login';
        $param = $request->request->all();
        $r1Service = $this->get('r1.remitter_service');
        $form = $this->createPostForm(LoginType::class);
        $form->handleRequest($request);
        if (!$form->isValid()) {
            return $this->show($form->getErrors(), null, 400);
        }
        $formData = $form->getData();
        $response = $r1Service->login($formData);

        $statusCode = 200;
        if (!$response->isSuccess()) {
            $statusCode = 400;
        }
        $this->get('log')->execute($param, $type, $response);

        return $this->show($response->getForClient(), null, $statusCode);
    }



    /**
     * Remmiter Login with pin
     *
     * @ApiDoc(
     *     section="Remitter",
     *     resource="Remitters",
     *      input={
     *   "name"="",
     *   "class"="Somtel\RemitOneBundle\Form\LoginpinType"
     *  },
     * )
     *
     * @Post("/remitter/loginpin")
     *
     */

    public function postRemitterloginpinAction(Request $request)
    {
        $type = 'login-pin';
        $param = $request->request->all();
        $r1Service = $this->get('r1.remitter_service');

        $form = $this->createPostForm(LoginpinType::class);
        $form->handleRequest($request);

        if (!$form->isValid()) {
            return $this->show($form->getErrors(), null, 400);
        }

        $formData = $form->getData();
        $response = $r1Service->loginPin($formData);
        $this->get('log')->execute($param, $type, $response);

        return $this->show($response->getForClient(), null, 200);
    }

    /**
     * forgot password
     *
     * @ApiDoc(
     *     section="Remitter",
     *     resource="Remitters",
     *      input={
     *   "name"="",
     *   "class"="Somtel\RemitOneBundle\Form\forgotPasswordType"
     *  },
     * )
     *
     * @Post("/remitter/forgot-password")
     *
     */

    public function postRemitterForgotPasswordAction(Request $request)
    {
        $type = 'forgot-password';
        $param = $request->request->all();
        $r1Service = $this->get('r1.remitter_service');

        $form = $this->createPostForm(forgotPasswordType::class);
        $form->handleRequest($request);

        if (!$form->isValid()) {
            return $this->show($form->getErrors(), null, 400);
        }

        $formData = $form->getData();
        $response = $r1Service->forgotPassword($formData);
        $this->get('log')->execute($param, $type, $response);

        return $this->show($response->getForClient(), null, 200);
    }

    /**
     * get remitter Profile info
     *
     * @ApiDoc(
     *     section="Remitter",
     *     resource="Remitters",
     *      input={
     *   "name"="",
     *   "class"="Somtel\RemitOneBundle\Form\getProfileType"
     *  },
     * )
     *
     * @Post("/remitter/get-profile")
     *
     */

    public function postGetprofileAction(Request $request)
    {
        $type = 'get-profile';
        $param = $request->request->all();
        $r1Service = $this->get('r1.remitter_service');

        $form = $this->createPostForm(getProfileType::class);
        $form->handleRequest($request);

        if (!$form->isValid()) {
            return $this->show($form->getErrors(), null, 400);
        }

        $formData = $form->getData();
        $response = $r1Service->getProfile($formData);
        $this->get('log')->execute($param, $type, $response);

        return $this->show($response->getForClient(), null, 200);
    }

    /**
     * edit remitter details
     *
     * @ApiDoc(
     *     section="Remitter",
     *     resource="Remitters",
     *      input={
     *   "name"="",
     *   "class"="Somtel\RemitOneBundle\Form\editRemitterType"
     *  },
     * )
     *
     * @Post("/remitter/edit")
     *
     */

    public function postEditAction(Request $request)
    {
        $type = 'edit';
        $param = $request->request->all();
        $r1Service = $this->get('r1.remitter_service');

        $form = $this->createPostForm(editRemitterType::class);
        $form->handleRequest($request);

        if (!$form->isValid()) {
            return $this->show($form->getErrors(), null, 400);
        }

        $formData = $form->getData();
        $response = $r1Service->updateRemitter($formData);
        $this->get('log')->execute($param, $type, $response);


        return $this->show($response->getForClient(), null, 200);
    }

    /**
     * create remitter
     *
     * @ApiDoc(
     *     section="Remitter",
     *     resource="Remitters",
     *      input={
     *   "name"="",
     *   "class"="Somtel\RemitOneBundle\Form\createRemitterType"
     *  },
     * )
     *
     * @Post("/remitter/quick-register")
     *
     */

    public function postQuickRegisterAction(Request $request)
    {
        $type = 'quick-Register';
        $param = $request->request->all();

        $r1Service = $this->get('r1.remitter_service');

        $form = $this->createPostForm(createRemitterType::class);
        $form->handleRequest($request);
         
        if (!$form->isValid()) {
            return $this->show($form->getErrors(), null, 400);
        }
        $path = 'https://retailminded.com/wp-content/uploads/2013/02/OnlineOutreach.jpg';
        $data = file_get_contents($path);
        $base64Img = base64_encode($data);
        $formData = $form->getData();
        $paramValArray = $param;

        $formData +=[
            "name" => $paramValArray['name'],
            "fname" => $paramValArray['fname'],
            "lname" => $paramValArray['lname'],
            "address1" => 'Street1',
            "country_id" => '02',
            "dob" =>  $paramValArray['dob'],
            "telephone" => '+1'.$paramValArray['telephone'],
            "verify_password" => $formData['password'],
            "id1_type" => $paramValArray['name'],
            "id1_details" => 'talkremmit Shweta',
            "id1_expiry" => '2017-10-11',
            "id1_scan" => $base64Img,
            "postcode" => $paramValArray['name'],
            "account_number" => $paramValArray['account_number'],
            "source_country_id" => '02',
            "nationality" => 'US',
            "toc" => true
        ];
        
        $response = $r1Service->registerRemitter($formData);
        $this->get('log')->execute($param, $type, $response);

        return $this->show($response->getForClient(), null, 200);
    }

    /**
     * create remitter
     *
     * @ApiDoc(
     *     section="Remitter",
     *     resource="Remitters",
     *      input={
     *   "name"="",
     *   "class"="Somtel\RemitOneBundle\Form\ConfirmRegisterType"
     *  },
     * )
     *
     * @Post("/remitter/confirm-register")
     *
     */

    public function postConfirmRegisterAction(Request $request)
    {
        $type = 'confirm-Register';
        $param = $request->request->all();
        $r1Service = $this->get('r1.remitter_service');

        $form = $this->createPostForm(confirmRegisterType::class);
        $form->handleRequest($request);

       /* if (!$form->isValid()) {
            return $this->show($form->getErrors(), null, 400);
        }*/

        $formData = $form->getData();

        $response = $r1Service->confirmRegistration($formData);
        $this->get('log')->execute($param, $type, $response);

        return $this->show($response->getForClient(), null, 200);
    }


    /**
     * change password
     *
     * @ApiDoc(
     *     section="Remitter",
     *     resource="Remitters",
     *      input={
     *   "name"="",
     *   "class"="Somtel\RemitOneBundle\Form\changePasswordType"
     *  },
     * )
     *
     * @Post("/remitter/change-password")
     *
     */

    public function postRemitterChangePasswordAction(Request $request)
    {
        $type = 'change-password';
        $param = $request->request->all();
        $r1Service = $this->get('r1.remitter_service');

        $form = $this->createPostForm(changePasswordType::class);
        $form->handleRequest($request);

        if(!$form->isValid()) {
            return $this->show($form->getErrors(), null, 400);
        }

        $formData = $form->getData();
        if ($formData['session_token'] == '' && $formData['forgot_password_token'] == '') {
            $error = ['status'=> Status::FAILURE, 'message' =>'session_token or forgot_password_token should not ber blank'];
            return $this->show($error);
        }

        $response = $r1Service->changePassword($formData);
        $this->get('log')->execute($param, $type, $response);
        return $this->show($response->getForClient(), null, 200);
    }
}
