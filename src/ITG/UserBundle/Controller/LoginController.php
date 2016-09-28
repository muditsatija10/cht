<?php

namespace ITG\UserBundle\Controller;

use FOS\RestBundle\Controller\Annotations\Post;
use FOS\RestBundle\Util\Codes;
use ITG\MillBundle\Controller\BaseController;
use AppBundle\Entity\Token;
use ITG\UserBundle\Form\LoginType;
use ITG\UserBundle\Util\Err;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AuthenticationCredentialsNotFoundException;

class LoginController extends BaseController
{
    /**
     * Login user and create token
     *
     * @ApiDoc(
     *  resource="Login",
     *  section="Users",
     *  input={
     *   "name"="",
     *   "class"="ITG\UserBundle\Form\LoginType"
     *  },
     *  output={
     *   "class"="AppBundle\Entity\Token"
     *  }
     * )
     *
     * @Post("/login")
     */
    public function postLoginAction(Request $request)
    {
        $form = $this->postLoginForm($request);

        $this->postLoginHandle($request, $form);
        if ($form->isValid())
        {
            return $this->postLoginValid($request, $form);
        }

        return $this->postLoginInvalid($request, $form);
    }

    protected function postLoginForm(Request $request)
    {
        return $this->createPostForm(LoginType::class);
    }

    protected function postLoginHandle(Request $request, FormInterface $form)
    {
        $form->handleRequest($request);
    }

    protected function postLoginValid(Request $request, FormInterface $form)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository('AppBundle:User')->findOneBy([
            'username' => $form->get('username')->getData()
        ]);

        if ($user)
        {
            if(password_verify($form->get('password')->getData(), $user->getPassword()))
            {
                $token = new Token();
                $token
                    ->setUser($user)
                    ->setToken($this->get('itg_mill.guid_generator')->generate())
                    ->setActivated(new \DateTime())
                ;
                $em->persist($token);
                $em->flush();
                return $this->returnToken($token);
            }
            else
            {
                throw $this->createNotFoundException();
                //return $this->error('Incorrect data', Err::BUNDLE, null, null, Codes::HTTP_UNAUTHORIZED);
            }
        }
        else
        {
            throw $this->createNotFoundException();
            //return $this->error('Incorrect data', Err::BUNDLE, null, null, Codes::HTTP_UNAUTHORIZED);
        }
    }

    protected function postLoginInvalid(Request $request, FormInterface $form)
    {
        return $this->formError($form);
    }

    protected function returnToken(Token $token = null)
    {
        return $this->show($token, ['id', 'token_list', 'user_list', 'user_details', 'role_list']);
    }

    /**
     * Get currently logged in token
     *
     * @ApiDoc(
     *  resource="Login",
     *  section="Users",
     *  output={
     *   "class"="AppBundle\Entity\Token",
     *   "groups"={"token_list"}
     *  }
     * )
     */
    public function getLoginCurrentAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $token = $em->getRepository('AppBundle:Token')->findOneBy([
            'token' => $request->headers->get('X-AUTH-TOKEN')
        ]);

        return $this->returnToken($token);
    }

    /**
     * Logout current token
     *
     * @ApiDoc(
     *  resource="Login",
     *  section="Users"
     * )
     */
    public function getLogoutAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $token = $em->getRepository('AppBundle:Token')->findOneBy([
            'token' => $request->headers->get('X-AUTH-TOKEN')
        ]);

        $token->setInactivated(new \DateTime());
        $em->flush();

        return $this->show();
    }

    /**
     * Logout all active tokens
     *
     * @ApiDoc(
     *  resource="Login",
     *  section="Users"
     * )
     */
    public function getLogoutAllAction()
    {
        $user = $this->getUser();
        $em = $this->getDoctrine()->getManager();
        $tokens = $em->getRepository('AppBundle:Token')->findBy([
            'user' => $user,
            'inactivated' => null
        ]);

        foreach($tokens as $token)
        {
            $token->setInactivated(new \DateTime());
        }

        $em->flush();
        return $this->show();
    }
}
