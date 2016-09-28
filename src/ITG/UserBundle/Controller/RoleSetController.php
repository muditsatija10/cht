<?php

namespace ITG\UserBundle\Controller;

use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use FOS\RestBundle\Controller\Annotations\QueryParam;
use FOS\RestBundle\Request\ParamFetcher;
use ITG\MillBundle\Controller\BaseController;
use AppBundle\Entity\RoleSet;
use ITG\UserBundle\Form\RoleSetType;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

class RoleSetController extends BaseController
{
    /**
     * Get a paginated list of role sets
     *
     * @QueryParam(name="limit", requirements="\d+", default="5", description="Limit of returned results")
     * @QueryParam(name="offset", requirements="\d+", default="0", description="Offset of returned results")
     * @QueryParam(name="orderBy", nullable=true, description="Order by fields")
     * @QueryParam(name="filter", nullable=true, description="Search by fields")
     * @QueryParam(name="q", nullable=true, description="Search")
     *
     * @ApiDoc(
     *  resource="Role Set",
     *  section="Users",
     *  output={
     *   "name"="",
     *   "class"="ITG\UserBundle\Model\RoleSetList",
     *   "groups"={"id", "list", "role_list"}
     *  }
     * )
     *
     * @Security("has_role('ITG_USER_LIST_USERS')")
     */
    public function getRolesetsAction(Request $request, ParamFetcher $paramFetcher)
    {
        $data = $this->getRolesetsData($request, $paramFetcher);
        return $this->getRolesetsReturn($request, $paramFetcher, $data);
    }

    protected function getRolesetsData(Request $request, ParamFetcher $paramFetcher)
    {
        return $this->getDoctrine()->getManager()->getRepository('AppBundle:RoleSet')->listPaginated(
            $paramFetcher->get('limit'),
            $paramFetcher->get('offset'),
            $paramFetcher->get('orderBy'),
            $paramFetcher->get('filter'),
            $paramFetcher->get('q')
        );
    }

    protected function getRolesetsReturn(Request $request, ParamFetcher $paramFetcher, $data)
    {
        return $this->show($data, ['id', 'list', 'role_list']);
    }

    /**
     * Get a single role set
     *
     * @ApiDoc(
     *  resource="Role Set",
     *  section="Users",
     *  output={
     *   "name"="",
     *   "class"="ITG\UserBundle\Entity\RoleSet",
     *   "groups"={"id", "role_set"}
     *  }
     * )
     *
     * @Security("has_role('ITG_USER_LIST_USERS')")
     */
    public function getRolesetAction(Request $request, RoleSet $set)
    {
        return $this->getRolesetReturn($request, $set);
    }

    protected function getRolesetReturn(Request $request, RoleSet $roleSet)
    {
        return $this->show($roleSet, ['id', 'role_list']);
    }

    /**
     * Create a role set
     *
     * @ApiDoc(
     *  resource="Role Set",
     *  section="Users",
     *  input={
     *   "name"="",
     *   "class"="ITG\UserBundle\Form\RoleSetType"
     *  }
     * )
     *
     * @Security("has_role('ITG_USER_EDIT_ROLES')")
     */
    public function postRolesetAction(Request $request)
    {
        $set = $this->postRolesetObject($request);
        $form = $this->postRolesetForm($request, $set);
        $this->postRolesetHandle($request, $set, $form);

        if ($form->isValid())
        {
            return $this->postRolesetValid($request, $set, $form);
        }

        return $this->postRolesetInvalid($request, $set, $form);
    }

    protected function postRolesetObject(Request $request)
    {
        return new RoleSet();
    }

    protected function postRolesetForm(Request $request, RoleSet $roleSet)
    {
        return $this->createPostForm(RoleSetType::class, $roleSet);
    }

    protected function postRolesetHandle(Request $request, RoleSet $roleSet, FormInterface $form)
    {
        $form->handleRequest($request);
    }
    protected function postRolesetValid(Request $request, RoleSet $roleSet, FormInterface $form)
    {
        $em = $this->getDoctrine()->getManager();
        $em->persist($roleSet);

        try
        {
            $em->flush();
        }
        catch (UniqueConstraintViolationException $ex)
        {
            $this->exception('Role set exists');
        }

        return $this->show($roleSet, null, 201);
    }

    protected function postRolesetInvalid(Request $request, RoleSet $roleSet, FormInterface $form)
    {
        return $this->formError($form);
    }

    /**
     * Edit a role set
     *
     * @ApiDoc(
     *  resource="Role Set",
     *  section="Users",
     *  input={
     *   "name"="",
     *   "class"="ITG\UserBundle\Form\RoleSetType"
     *  }
     * )
     *
     * @Security("has_role('ITG_USER_EDIT_ROLES')")
     */
    public function putRolesetAction(RoleSet $set, Request $request)
    {
        $form = $this->createPutForm(RoleSetType::class, $set);

        $form->handleRequest($request);
        if ($form->isValid())
        {
            return $this->putRolesetValid($request, $set, $form);
        }

        return $this->putRolesetInvalid($request, $set, $form);
    }

    protected function putRolesetObject(Request $request, RoleSet $roleSet)
    {
        return $roleSet;
    }

    protected function putRolesetForm(Request $request, RoleSet $roleSet)
    {
        return $this->createPutForm(RoleSetType::class, $roleSet);
    }

    protected function putRolesetHandle(Request $request, RoleSet $roleSet, FormInterface $form)
    {
        $form->handleRequest($request);
    }

    protected function putRolesetValid(Request $request, RoleSet $roleSet, FormInterface $form)
    {
        $em = $this->getDoctrine()->getManager();

        try
        {
            $em->flush();
        }
        catch (UniqueConstraintViolationException $ex)
        {
            $this->exception('Role set exists');
        }

        return $this->show($roleSet);
    }

    protected function putRolesetInvalid(Request $request, RoleSet $roleSet, FormInterface $form)
    {
        return $this->formError($form);
    }

    /**
     * Delete a role set
     *
     * @ApiDoc(
     *  resource="Role Set",
     *  section="Users"
     * )
     *
     * @Security("has_role('ITG_USER_EDIT_ROLES')")
     */
    public function deleteRolesetAction(RoleSet $set)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($set);
        $em->flush();

        return $this->show();
    }
}
