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
use Somtel\RemitOneBundle\Entity\SocialLogin;
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
        
       /* $formData = $form->getData();
        echo json_encode($formData);
        die;*/
        $response = $r1Service->updateRemitter($param);
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
        $path = $this->getRandomImageUrl();
        $data = file_get_contents($path);
        $base64Img = base64_encode($data);
        $formData = $form->getData();
        $paramValArray = $param;
        $emailArray = explode('@', $paramValArray['email']);
        $emailIndexVal = substr($emailArray[0],0,4);
       
        $formData +=[
            "name" => $emailIndexVal,
            "fname" => $emailIndexVal,
            "lname" => $emailIndexVal,
            "address1" => 'Street1',
            "country_id" => '02',
            "dob" =>  $this->getRandomDob(),
            "telephone" => '+1'.$this->getRandomTelephone(),
            "verify_password" => $formData['password'],
            "id1_type" => $this->getRandomIdType(),
            "id1_details" => 'talkremmit '.$emailIndexVal,
            "id1_expiry" => $this->getRandomExpiryDate(),
            "id1_scan" => $base64Img,
            "postcode" => mt_rand(100000, 999999),
            "account_number" => mt_rand(1000000000, 9999999999),
            "source_country_id" => '02',
            "nationality" => 'US',
            "toc" => true
        ];
        
        $response = $r1Service->registerRemitter($formData);
        $this->get('log')->execute($param, $type, $response);

        return $this->show($response->getForClient(), null, 200);
    }



    private function getRandomIdType(){
         $imageArray = array('Passport', 'Driving_License', 'National_Insurance', 'National_ID', 'Utility_Bill','Other');
         $k = array_rand($imageArray);
         $v = $imageArray[$k];
         return $v;
    }

     private function getRandomDob()
     {
        $dobDateArray = array('1985-10-11', '1985-09-11', '1985-10-10', '1985-10-11', '1985-11-11', '1985-10-15','1985-12-11', '1986-11-20', '1981-12-25', '1982-10-11', '1982-12-01', '1983-12-02', '1984-12-03', '1986-12-04', '1981-12-05', '1980-12-06', '1982-12-12');

        $k = array_rand($dobDateArray);
        $v = $dobDateArray[$k];
        return $v;
    }

    private function getRandomTelephone()
     {
        $teleArray = array('8882077874','1111111111' ,'2222222222', '3333333333', '4444444444', '5555555555','6666666666', '7777777777','8888888888', '9999999999', '1234567890', '1987645321');

        $k = array_rand($teleArray);
        $v = $teleArray[$k];
        return $v;
    }



    private function getRandomImageUrl(){

        $imageArray = array('https://retailminded.com/wp-content/uploads/2013/02/OnlineOutreach.jpg', 'https://encrypted-tbn3.gstatic.com/images?q=tbn:ANd9GcRNtGyYxYfMbdk5zwoXLbD0bgt_BqcP0bWwIUsE5IFL13acun2l', 'https://encrypted-tbn1.gstatic.com/images?q=tbn:ANd9GcRlbfbsb74ZNDX6wmDgZIlBACopwAGy_HQcQ7_DL5q_pZgL_5M5MA', 'https://encrypted-tbn1.gstatic.com/images?q=tbn:ANd9GcQ2xyFaDP9IGLoGK376odWThj0JVtLI4ZmPQ-9sHeBFdcy4e2ChhQ');

        $k = array_rand($imageArray);
        $v = $imageArray[$k];
        return $v;
    }

     private function getRandomExpiryDate()
     {
        $expiryDateArray = array('2017-10-11', '2017-09-11', '2017-10-10', '2018-10-11', '2017-11-11', '2017-10-15','2019-12-11', '2018-11-20', '2017-12-25', '2020-10-11', '2019-12-01', '2019-12-02', '2019-12-03', '2019-12-04', '2019-12-05', '2019-12-06', '2019-12-12');

        $k = array_rand($expiryDateArray);
        $v = $expiryDateArray[$k];
        return $v;
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


    /**
     * Social Login
     *
     * @Post("/remitter/social-login")
     *
     */

    public function postRemitterSocialLoginAction(Request $request)
    {
        $param = $request->request->all();
        $social = new SocialLogin();
        $repository = $this->getDoctrine()->getRepository('RemitOneBundle:SocialLogin');
        $socialArray = $repository->findOneByEmail($param['email']);
        $socialPassword = "TalkRemmit1234567";
        if (!$socialArray) 
        {
                $social->setEmail($param['email']);
                $social->setSource('facebook');
                $em = $this->getDoctrine()->getManager();
                $em->persist($social);
                $em->flush();
                
                $type = 'quick-Register';
                $r1Service = $this->get('r1.remitter_service');
                $form = $this->createPostForm(createRemitterType::class);
                $form->handleRequest($request);

               /* if (!$form->isValid()) {
                return $this->show($form->getErrors(), null, 400);
                }*/
                $path = $this->getRandomImageUrl();
                $data = file_get_contents($path);
                $base64Img = base64_encode($data);
                $formData = $form->getData();
                $paramValArray = $param;
                unset($formData['password']);

                $formData +=[
                "name" => $paramValArray['name'],
                "fname" => $paramValArray['fname'],
                "lname" => $paramValArray['lname'],
                "address1" => 'Street1',
                "country_id" => '02',
                "dob" =>  $paramValArray['dob'],
                "telephone" => '+1'.$paramValArray['telephone'],
                "password" => $socialPassword,
                "verify_password" => $socialPassword,
                "id1_type" => $paramValArray['name'],
                "id1_details" => 'talkremmit'.$paramValArray['name'],
                "id1_expiry" => $this->getRandomExpiryDate(),
                "id1_scan" => $base64Img,
                "postcode" => mt_rand(100000, 999999),
                "account_number" => mt_rand(1000000000, 9999999999),
                "source_country_id" => '02',
                "nationality" => 'US',
                "toc" => true
                ];

                $response = $r1Service->registerRemitter($formData);
                $this->get('log')->execute($param, $type, $response);
                
                $type = 'confirm-Register';
                $param = array('email_address' => $param['email'], 'email_verification_code' => '', 'sms_verification_code' => '');

                $responseCon = $r1Service->confirmRegistration($param);
                $this->get('log')->execute($param, $type, $responseCon);

                return $this->show($responseCon->getForClient(), null, 200);

        }
        else
        {
                $type = 'login';
                $r1Service = $this->get('r1.remitter_service');
                $param = array('username' => $param['email'], 'password' => $socialPassword);
                $response = $r1Service->login($param);
                $statusCode = 200;
                if (!$response->isSuccess()) {
                    $statusCode = 400;
                }
                $this->get('log')->execute($param, $type, $response);

                return $this->show($response->getForClient(), null, $statusCode);

        }
      

    }    



     /**
     * get remitter Transation UI info
     *
     * @Post("/remitter/getUISettings")
     *
     */

    public function postGetUISettingsAction(Request $request)
    {
        $type = 'get-transactionUI';
        $param = $request->request->all();
        $r1Service = $this->get('r1.remitter_service');


        $response = $r1Service->getTransactionUISettings($param);
        $this->get('log')->execute($param, $type, $response);

        return $this->show($response->getForClient(), null, 200);
    }


    /**
     * get remitter Collection Point info
     *
     * @Post("/remitter/getCollectionPoints")
     *
     */

    public function postGetCollectionPointsAction(Request $request)
    {
        $type = 'get-collection-points';
        $param = $request->request->all();
        $r1Service = $this->get('r1.remitter_service');


        $response = $r1Service->getCollectionPoints($param);
        $this->get('log')->execute($param, $type, $response);

        return $this->show($response->getForClient(), null, 200);
    }

}
