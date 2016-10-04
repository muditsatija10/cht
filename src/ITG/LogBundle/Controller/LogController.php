<?php

namespace ITG\LogBundle\Controller;

use AppBundle\Entity\Log;
use FOS\RestBundle\Controller\Annotations\QueryParam;
use FOS\RestBundle\Request\ParamFetcher;
use FOS\RestBundle\Util\Codes;
use ITG\LogBundle\Form\LogType;
use ITG\MillBundle\Controller\BaseController;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;

class LogController extends BaseController
{
    /**
     * Get a paginated list of logs
     *
     * @QueryParam(name="limit", requirements="\d+", default="5", description="Limit of returned results")
     * @QueryParam(name="offset", requirements="\d+", default="0", description="Offset of returned results")
     * @QueryParam(name="orderBy", nullable=true, description="Order by fields")
     * @QueryParam(name="filter", nullable=true, description="Search by fields")
     * @QueryParam(name="q", nullable=true, description="Search")
     *
     * @ApiDoc(
     *  resource="Log",
     *  section="Logs",
     *  output={
     *   "name"="",
     *   "class"="ITG\LogBundle\Model\LogList",
     *   "groups"={"id", "list", "log_list"}
     *  }
     * )
     *
     * @Security("has_role('ITG_LOG_LIST_LOGS')")
     */
    public function getLogsAction(ParamFetcher $paramFetcher)
    {
        $sort = $paramFetcher->get('orderBy');
        if(!$sort)
        {
            $sort = [
                'date' => 'DESC'
            ];
        }

        $logs = $this->getDoctrine()->getManager()->getRepository('AppBundle:Log')->listPaginated(
            $paramFetcher->get('limit'),
            $paramFetcher->get('offset'),
            $sort,
            $paramFetcher->get('filter'),
            $paramFetcher->get('q')
        )
        ;

        return $this->show($logs, ['id', 'list', 'log_list']);
    }

    /**
     * Get a single log
     *
     * @ApiDoc(
     *     resource="Log",
     *     section="Logs"
     * )
     *
     * @Security("has_role('ITG_LOG_LIST_LOGS')")
     */
    public function getLogAction(Log $log)
    {
        return $this->show($log, ['id', 'log_list', 'log_details']);
    }

    /**
     * Post a log
     *
     * @ApiDoc(
     *     resource="Log",
     *     section="Logs",
     *     input={
     *      "name"="",
     *      "class"="ITG\LogBundle\Form\LogType"
     *     }
     * )
     */
    public function postPubliclogAction(Request $request)
    {
        $log = new Log();
        $form = $this->createPostForm(LogType::class, $log);

        $form->handleRequest($request);
        if($form->isValid())
        {
            // Set project from config
            if(!$log->getProject())
            {
                $log->setProject($this->getParameter('itg_log')['project']);
            }

            $em = $this->getDoctrine()->getManager();
            $em->persist($log);
            $em->flush();

            return $this->show($log, null, Codes::HTTP_CREATED);
        }
        
        return $this->show('Bad request', null, 400);
    }
}
