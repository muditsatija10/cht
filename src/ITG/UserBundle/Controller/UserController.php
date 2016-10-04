<?php

namespace ITG\UserBundle\Controller;

use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use FOS\RestBundle\Controller\Annotations\Post;
use FOS\RestBundle\Controller\Annotations\Put;
use FOS\RestBundle\Controller\Annotations\QueryParam;
use FOS\RestBundle\Request\ParamFetcher;
use ITG\MillBundle\Controller\BaseController;
use AppBundle\Entity\User;
use ITG\MillBundle\Form\UploadType;
use ITG\UserBundle\Form\UserSettingsType;
use ITG\UserBundle\Form\UserType;
use ITG\UserBundle\Util\Err;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\Request;

class UserController extends BaseController
{

    /**
     * Get all available roles
     *
     * @ApiDoc(
     *  resource="User",
     *  section="Users"
     * )
     *
     * @Security("has_role('ITG_USER_LIST_USERS')")
     */
    public function getUsersRolesAction()
    {
        $config = $this->getParameter('itg_user');
        return $this->show($config['roles']);
    }

    /**
     * Get a paginated list of users
     *
     * @QueryParam(name="limit", requirements="\d+", default="5", description="Limit of returned results")
     * @QueryParam(name="offset", requirements="\d+", default="0", description="Offset of returned results")
     * @QueryParam(name="orderBy", nullable=true, description="Order by fields")
     * @QueryParam(name="filter", nullable=true, description="Search by fields")
     * @QueryParam(name="q", nullable=true, description="Search")
     *
     * @ApiDoc(
     *  resource="User",
     *  section="Users",
     *  output={
     *   "name"="",
     *   "class"="ITG\UserBundle\Model\UserList",
     *   "groups"={"id", "list", "user_list"}
     *  }
     * )
     *
     * @Security("has_role('ITG_USER_LIST_USERS')")
     */
    public function getUsersAction(Request $request, ParamFetcher $paramFetcher)
    {
        $users = $this->getUsersData($request, $paramFetcher);
        return $this->getUsersReturn($request, $paramFetcher, $users);
    }

    protected function getUsersData(Request $request, ParamFetcher $paramFetcher)
    {
        return $this->getDoctrine()->getManager()->getRepository('AppBundle:User')->listPaginated(
            $paramFetcher->get('limit'),
            $paramFetcher->get('offset'),
            $paramFetcher->get('orderBy'),
            $paramFetcher->get('filter'),
            $paramFetcher->get('q')
        );
    }

    protected function getUsersReturn(Request $request, ParamFetcher $paramFetcher, $data)
    {
        return $this->show($data, ['id', 'list', 'user_list']);
    }

    /**
     * Get a single user
     *
     * @ApiDoc(
     *  resource="User",
     *  section="Users",
     *  output={
     *   "name"="",
     *   "class"="ITG\UserBundle\Entity\User",
     *   "groups"={"id", "user_list", "user_details", "role_list"}
     *  }
     * )
     *
     * @Security("has_role('ITG_USER_LIST_USERS')")
     */
    public function getUserAction(Request $request, User $user)
    {
        return $this->getUserReturn($request, $user);
    }

    protected function getUserReturn(Request $request, User $user)
    {
        return $this->show($user, ['id', 'user_list', 'user_details', 'role_list']);
    }

    /**
     * Create a user
     *
     * @ApiDoc(
     *  resource="User",
     *  section="Users",
     *  input={
     *   "name"="",
     *   "class"="ITG\UserBundle\Form\UserType"
     *  }
     * )
     *
     * @Security("has_role('ITG_USER_EDIT_USERS')")
     */
    public function postUserAction(Request $request)
    {
        $user = $this->postUserObject($request);
        $form = $this->postUserForm($request, $user);
        $this->postUserHandle($request, $user, $form);

        if ($form->isValid())
        {
            return $this->postUserValid($request, $user, $form);
        }

        return $this->postUserInvalid($request, $user, $form);
    }

    protected function postUserObject(Request $request)
    {
        return new User();
    }

    protected function postUserForm(Request $request, User $user)
    {
        $builder = $this->createPostFormBuilder(UserType::class, $user, ['validation_groups' => ['Default', 'new']]);
        $this->addPasswordEncoderToForm($builder);
        return $builder->getForm();
    }

    protected function postUserHandle(Request $request, User $user, FormInterface $form)
    {
        $form->handleRequest($request);
    }

    protected function postUserValid(Request $request, User $user, FormInterface $form)
    {
        $em = $this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush();

        return $this->postUserReturn($request, $user, $form);
    }

    protected function postUserReturn(Request $request, User $user, FormInterface $form)
    {
        return $this->show($user, ['id', 'user_list', 'user_details', 'role_list'], 201);
    }

    protected function postUserInvalid(Request $request, User $user, FormInterface $form)
    {
        return $this->formError($form);
    }

    /**
     * Edit a user
     *
     * @ApiDoc(
     *  resource="User",
     *  section="Users",
     *  input={
     *   "name"="",
     *   "class"="ITG\UserBundle\Form\UserType"
     *  }
     * )
     *
     * @Security("has_role('ITG_USER_EDIT_USERS')")
     */
    public function putUserAction(User $user, Request $request)
    {
        $this->putUserObject($request, $user);
        $form = $this->putUserForm($request, $user);
        $this->putUserHandle($request, $user, $form);

        if ($form->isValid())
        {
            return $this->putUserValid($request, $user, $form);
        }

        return $this->putUserInvalid($request, $user, $form);
    }

    protected function putUserObject(Request $request, User $user)
    {
        return $user;
    }

    protected function putUserForm(Request $request, User $user)
    {
        $builder = $this->createPutFormBuilder(UserType::class, $user, ['validation_groups' => ['Default']]);
        $this->addPasswordEncoderToForm($builder);
        return $builder->getForm();
    }

    protected function putUserHandle(Request $request, User $user, FormInterface $form)
    {
        $form->handleRequest($request);
    }

    protected function putUserValid(Request $request, User $user, FormInterface $form)
    {
        $em = $this->getDoctrine()->getManager();
        $em->flush();

        return $this->putUserReturn($request, $user, $form);
    }

    protected function putUserReturn(Request $request, User $user, FormInterface $form)
    {
        return $this->show($user, ['id', 'user_list', 'user_details', 'role_list']);
    }

    protected function putUserInvalid(Request $request, User $user, FormInterface $form)
    {
        return $this->formError($form);
    }

    /**
     * Delete a user
     *
     * @ApiDoc(
     *  resource="User",
     *  section="Users"
     * )
     *
     * @Security("has_role('ITG_USER_DELETE_USERS')")
     */
    public function deleteUserAction(User $user)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($user);
        $em->flush();

        return $this->show();
    }

    /**
     * Upload user image
     *
     * @ApiDoc(
     *  resource="User",
     *  section="Users"
     * )
     *
     * @Post("/user/avatar")
     */
    public function postUserAvatarAction(Request $request)
    {
        $form = $this->postUserAvatarForm($request);
        $this->postUserAvatarHandle($request, $form);

        if($form->isValid())
        {
            return $this->postUserAvatarValid($request, $form);
        }

        return $this->postUserAvatarInvalid($request, $form);
    }

    protected function postUserAvatarForm(Request $request)
    {
        return $this->createPostForm(UploadType::class);
    }

    protected function postUserAvatarHandle(Request $request, FormInterface $form)
    {
        $form->handleRequest($request);
    }

    protected function postUserAvatarValid(Request $request, FormInterface $form)
    {
        $user = $this->getUser();

        /** @var File $file */
        $file = $form->get('file')->getData();
        $ext = $request->files->get('file')->getClientOriginalExtension();

        // TODO: get file mime type and allow only images

        $filename = $this->getParameter('kernel.root_dir') . '/../images/avatars/' . $user->getId() . '.png';
        $originalName = $file->getPath() . '/' .$file->getBasename();
        //$file->move($this->getParameter('kernel.root_dir') . '/../images/avatars');

        $user->setAvatar('images/avatars/' . $user->getId() . '.png');

        $src = null;
        switch($ext)
        {
            case 'png':
                $src = imagecreatefrompng($originalName);
                break;

            case 'jpg':
            case 'jpeg':
                $src = imagecreatefromjpeg($originalName);
                break;

            case 'gif':
                $src = imagecreatefromgif($originalName);
                break;

            default:
                $this->exception('Bad image type');
        }

        // Get image orientation
        $size = getimagesize($originalName);

        $scale = $size[0] < $size[1] ? 256 / $size[0] : 256 / $size[1];

        $thumb = imagecreatetruecolor(256, 256);
        imagecopyresampled($thumb, $src, 0, 0, ($size[0] - (256 / $scale)) / 2, ($size[1] - (256 / $scale)) / 2, 256, 256, 256 / $scale, 256 / $scale);

        imagepng($thumb, $filename);

        $this->getDoctrine()->getManager()->flush();

        return $this->show();
    }

    protected function postUserAvatarInvalid(Request $request, FormInterface $form)
    {
        return $this->formError($form);
    }

    /**
     * Update current user settings
     *
     * @ApiDoc(
     *  resource="User",
     *  section="Users"
     * )
     *
     * @Put("/user")
     */
    public function putUserCurrentAction(Request $request)
    {
        $user = $this->putUserCurrentObject($request);
        $form = $this->putUserCurrentForm($request, $user);
        $this->putUserCurrentHandle($request, $user, $form);

        if ($form->isValid())
        {
            return $this->putUserCurrentValid($request, $user, $form);
        }

        return $this->putUserCurrentInvalid($request, $user, $form);
    }

    protected function putUserCurrentObject(Request $request)
    {
        return $this->getUser();
    }

    protected function putUserCurrentForm(Request $request, User $user)
    {
        $builder = $this->createPutFormBuilder(UserSettingsType::class, $user);
        $this->addPasswordEncoderToForm($builder);
        return $builder->getForm();
    }

    protected function putUserCurrentHandle(Request $request, User $user, FormInterface $form)
    {
        $form->handleRequest($request);
    }

    protected function putUserCurrentValid(Request $request, User $user, FormInterface $form)
    {
        $em = $this->getDoctrine()->getManager();
        $em->flush();
        return $this->show($user);
    }

    protected function putUserCurrentInvalid(Request $request, User $user, FormInterface $form)
    {
        return $this->formError($form);
    }

    protected function addPasswordEncoderToForm(FormBuilderInterface $builder)
    {
        $builder->addEventListener(FormEvents::SUBMIT, function(FormEvent $event) {
            /** @var User $user */
            $user = $event->getData();
            $form = $event->getForm();

            if($psw = $form->get('password')->getData())
            {
                $user->setPassword(
                    $this->get('security.encoder_factory')
                        ->getEncoder($user)
                        ->encodePassword($psw, null)
                );
            }
        });
    }
}
