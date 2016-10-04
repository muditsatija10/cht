<?php

namespace ITG\UserBundle\Controller;

use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use FOS\RestBundle\Controller\Annotations\Post;
use ITG\MillBundle\Controller\BaseController;
use AppBundle\Entity\User;
use ITG\UserBundle\Form\RegistrationType;
use ITG\UserBundle\Util\Err;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

class RegistrationController extends BaseController
{
    /**
     * Register a new user
     *
     * @ApiDoc(
     *  resource="Registration",
     *  section="Users",
     *  input={
     *   "name"="",
     *   "class"="ITG\UserBundle\Form\RegistrationType"
     *  }
     * )
     *
     * @Post("/register")
     */
    public function postRegisterAction(Request $request)
    {
        $config = $this->getParameter('itg_user');
        $this->postRegistrationCheckEnabled($request, $config);

        $user = $this->postRegistrationObject($request);
        $form = $this->postRegistrationForm($request, $user);
        $this->postRegistrationHandle($request, $user, $form);

        if ($form->isValid())
        {
            return $this->postRegistrationValid($request, $user, $form);
        }

        return $this->postRegistrationInvalid($request, $user, $form);
    }

    protected function postRegistrationCheckEnabled(Request $request, $config)
    {
        if (!isset($config['registration']))
        {
            $this->exception('Registration is disabled'/*, Err::REGISTRATION_DISABLED*/);
        }
    }

    protected function postRegistrationObject(Request $request)
    {
        return new User();
    }

    protected function postRegistrationForm(Request $request, User $user)
    {
        return $this->createPostForm(RegistrationType::class, $user);
    }

    protected function postRegistrationHandle(Request $request, User $user, FormInterface $form)
    {
        $form->handleRequest($request);
    }

    protected function postRegistrationValid(Request $request, User $user, FormInterface $form)
    {
        $user->setRegistered(new \DateTime());

        $em = $this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush();

        return $this->show($user, null, 201);
    }

    protected function postRegistrationInvalid(Request $request, User $user, FormInterface $form)
    {
        return $this->formError($form);
    }
}
