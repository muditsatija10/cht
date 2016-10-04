<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Event;
use AppBundle\Entity\Something;
use AppBundle\Entity\Zone;
use AppBundle\Form\SomethingType;
use AppBundle\Form\ZoneType;
use AppBundle\Model\Zone\AccessModel;
use AppBundle\Util\ErrorCodes;
use FOS\RestBundle\Request\ParamFetcher;
use FOS\RestBundle\Controller\Annotations\QueryParam;
use ITG\MillBundle\Controller\BaseController;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use FOS\RestBundle\Util\Codes;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;

class SomethingController extends BaseController
{
    /**
     * Get a filtered list of somethings
     *
     * @QueryParam(name="offset", requirements="\d+", default="0", description="Offset of returned results")
     * @QueryParam(name="limit", requirements="\d+", default="5", description="Limit of returned results")
     * @QueryParam(name="orderBy", nullable=true, description="Order by fields")
     * @QueryParam(name="filter", nullable=true, description="Search by fields")
     * @QueryParam(name="q", nullable=true, description="Search")
     *
     * @ApiDoc(
     *     section="Project",
     *     resource="Something",
     *     output = {
     *         "class" = "AppBundle\Model\SomethingList",
     *         "groups" = {"id", "list", "something_list"}
     *     }
     * )
     *
     * @Security("has_role('PROJ_SOMETHING_LIST_SOMETHINGS')")
     */
    function getSomethingsAction(ParamFetcher $paramFetcher)
    {
        $data = $this->getDoctrine()->getRepository('AppBundle:Something')->listPaginated(
            $paramFetcher->get('limit'),
            $paramFetcher->get('offset'),
            $paramFetcher->get('orderBy') ? json_decode($paramFetcher->get('orderBy')) : null,
            $paramFetcher->get('filter'),
            $paramFetcher->get('q')
        );

        return $this->show($data, ['id', 'list', 'something_list']);
    }

    /**
     * Get a single something
     *
     * @ApiDoc(
     *     section="Project",
     *     resource="Something",
     *     output = {
     *         "name" = "",
     *         "class" = "AppBundle\Entity\Something",
     *         "groups" = {"id", "something_list", "something_detail"}
     *     }
     * )
     *
     * @Security("has_role('PROJ_SOMETHING_LIST_SOMETHINGS')")
     */
    function getSomethingAction(Something $something)
    {
        return $this->show($something, ['id', 'something_list', 'something_details']);
    }

    /**
     * Create new something
     *
     * @ApiDoc(
     *     section="Project",
     *     resource="Something",
     *     input = {
     *         "class" = "AppBundle\Form\SomethingType",
     *         "name" = ""
     *     }
     * )
     *
     * @Security("has_role('PROJ_SOMETHING_EDIT_SOMETHINGS')")
     */
    function postSomethingAction(Request $request)
    {
        $something = new Something();
        $form = $this->createPostForm(SomethingType::class, $something);

        $form->handleRequest($request);
        if($form->isValid())
        {
            $em = $this->getDoctrine()->getManager();

            $em->persist($something);
            $em->flush();

            return $this->show($something, ['id', 'something_list', 'something_details'], Codes::HTTP_CREATED);
        }

        return $this->show($form, null, 400);
    }

    /**
     * Edit something
     *
     * @ApiDoc(
     *     section="Project",
     *     resource="Something",
     *     input = {
     *         "class" = "AppBundle\Form\SomethingType",
     *         "name" = ""
     *     }
     * )
     *
     * @Security("has_role('PROJ_SOMETHING_EDIT_SOMETHINGS')")
     */
    function putSomethingAction(Request $request, Something $something)
    {
        $form = $this->createPutForm(SomethingType::class, $something);

        $form->handleRequest($request);
        if($form->isValid())
        {
            $em = $this->getDoctrine()->getManager();
            $em->flush();

            return $this->show($something, ['id', 'something_list', 'something_details']);
        }

        return $this->show($form, null, 400);
    }

    /**
     * Delete something
     *
     * @ApiDoc(
     *     section="Project",
     *     resource="Something"
     * )
     *
     * @Security("has_role('PROJ_SOMETHING_DELETE_SOMETHINGS')")
     */
    function deleteSomethingAction(Something $something)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($something);
        $em->flush();

        return $this->show();
    }
}