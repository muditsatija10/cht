<?php

namespace ITG\MillBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Util\Codes;
use ITG\MillBundle\Exception\VisibleException;
use ITG\MillBundle\Util\Err;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

class BaseController extends FOSRestController
{
    // =================================================================================================================
    // = Returns and Exceptions
    // =================================================================================================================

    /**
     * Create an exception that is visible to the client
     *
     * @param string $message A message to show in exception
     * @param int $errorCode Error code that is used by the frontend to identify this exception
     * @param object $object An object to attach to exception and show
     * @param int $code HTTP status code. Default is 400
     * @return VisibleException
     */
    protected function exception(
        $message,
        $errorCode = Err::BUNDLE,
        $object = null,
        $code = Codes::HTTP_BAD_REQUEST
    ) {
        return new VisibleException($message, $errorCode, $object, $code);
    }

    /**
     * Return an error in same format as exception, except without actually throwing an exception
     *
     * @param string $message
     * @param int $errorCode
     * @param null $object
     * @param array|null $groups
     * @param int $code
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function error(
        $message = 'Error',
        $errorCode = Err::BUNDLE,
        $object = null,
        array $groups = null,
        $code = Codes::HTTP_BAD_REQUEST
    ) {
        $obj = [
            'code' => $code,
            'errorCode' => $errorCode,
            'object' => $object,
            'message' => $message
        ];

        return $this->show($obj, $groups, $code);
    }

    /**
     * Return form errors to JSON
     *
     * @param FormInterface $form
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function formError(FormInterface $form)
    {
        $errorObj = $form->getErrors(true);
        return $this->show($errorObj, null, Codes::HTTP_BAD_REQUEST);
    }

    /**
     * Helper class to create a response with serializer groups and all that jazz
     *
     * @param object|string $data
     * @param array|null $serializerGroups
     * @param int $status
     * @param array $headers
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function show($data = null, $serializerGroups = null, $status = Codes::HTTP_OK, $headers = [])
    {
        $view = $this->view($data, $status, $headers);
        if ($serializerGroups)
        {
            $view->getSerializationContext()->setGroups($serializerGroups);
        }
        return $this->handleView($view);
    }

    // =================================================================================================================
    // = Form Control
    // =================================================================================================================

    /**
     * @param $type
     * @param null $data
     * @param array $options
     * @return \Symfony\Component\Form\FormBuilderInterface
     */
    protected function createPostFormBuilder($type = FormType::class, $data = null, array $options = [])
    {
        return $this->get('form.factory')->createNamedBuilder('', $type, $data, $options);
    }

    /**
     * @param string $type
     * @param mixed $data
     * @param array $options
     * @return \Symfony\Component\Form\Form|\Symfony\Component\Form\FormInterface
     */
    protected function createPostForm($type = FormType::class, $data = null, array $options = [])
    {
        return $this->createPostFormBuilder($type, $data, $options)->getForm();
    }

    /**
     * @param $type
     * @param null $data
     * @param array $options
     * @return \Symfony\Component\Form\FormBuilderInterface
     */
    protected function createPutFormBuilder($type = FormType::class, $data = null, array $options = [])
    {
        $options['method'] = 'PUT';
        return $this->get('form.factory')->createNamedBuilder('', $type, $data, $options);
    }

    /**
     * @param string $type
     * @param mixed $data
     * @param array $options
     * @return \Symfony\Component\Form\Form|\Symfony\Component\Form\FormInterface
     */
    protected function createPutForm($type = FormType::class, $data = null, array $options = [])
    {
        return $this->createPutFormBuilder($type, $data, $options)->getForm();
    }

    // =================================================================================================================
    // = Role Control
    // =================================================================================================================

    /**
     * Check if any of the provided roles are granted to user
     *
     * @param array $roles
     * @return bool
     */
    protected function anyRoleGranted(array $roles)
    {
        if(!$user = $this->getUser())
        {
            return false;
        }

        $userRoles = $user->getRoles();

        foreach ($roles as $role)
        {
            if(in_array($role, $userRoles))
            {
                return true;
            }
        }

        return false;
    }

    /**
     * Check if all of the provided roles are granted to user
     *
     * @param array $roles
     * @return bool
     */
    protected function allRolesGranted(array $roles)
    {
        if(!$user = $this->getUser())
        {
            return false;
        }

        $userRoles = $user->getRoles();

        foreach ($roles as $role)
        {
            if(!in_array($role, $userRoles))
            {
                return false;
            }
        }

        return true;
    }

    /**
     * Check if provided role is granted to user
     *
     * @param $role
     * @return bool
     */
    protected function roleGranted($role)
    {
        return $this->anyRoleGranted([$role]);
    }

    /**
     * Throw access denied exception if user does not have this role
     * @param $role
     * @param string $message
     */
    protected function denyUnlessGranted($role, $message = 'Access denied')
    {
        if(!$this->roleGranted($role))
        {
            throw $this->createAccessDeniedException($message);
        }
    }

    /**
     * Throw access denied exception if user does not have one or more provided roles
     *
     * @param array $roles
     * @param string $message
     */
    protected function denyUnlessAllGranted(array $roles, $message = 'Access denied')
    {
        if(!$this->allRolesGranted($roles))
        {
            throw $this->createAccessDeniedException($message);
        }
    }

    /**
     * Throw access denied exception if user does not have any of the provided roles
     *
     * @param array $roles
     * @param string $message
     */
    protected function denyUnlessAnyGranted(array $roles, $message = 'Access denied')
    {
        if(!$this->allRolesGranted($roles))
        {
            throw $this->createAccessDeniedException($message);
        }
    }

    // =================================================================================================================
    // = User Control
    // =================================================================================================================

    /**
     * @param Request $request
     * @return \AppBundle\Entity\User|null
     */
    protected function getTokenUser(Request $request)
    {
        $tokenString = $request->headers->get('X-AUTH-TOKEN');

        if(!$tokenString)
        {
            return null;
        }

        $token = $this->getDoctrine()->getRepository('AppBundle:Token')->findOneBy([
            'token' => $tokenString
        ]);

        if(!$token)
        {
            return null;
        }

        return $token->getUser();
    }
}
