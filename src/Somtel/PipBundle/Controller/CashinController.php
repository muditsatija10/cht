<?php

namespace Somtel\PipBundle\Controller;

use AppBundle\Entity\User;
use ITG\MillBundle;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Somtel\PipBundle;
use Somtel\PipBundle\Entity\PipCashinOrder;
use Symfony\Component\HttpFoundation;
use FOS\RestBundle\Util;
use Symfony\Component\DependencyInjection;
use Symfony\Component\HttpFoundation\Request;

class CashinController extends MillBundle\Controller\BaseController
{
    protected $listGroups = ['id', 'list', 'pip_cashin_order_list'];
    protected $detailGroups = ['id', 'pip_cashin_order_list', 'pip_cashin_order_details'];

    /**
     * Create a new cashin
     *
     * @ApiDoc(
     *     section="Pip",
     *     resource="Pip"
     * )
     */
    public function postCashinAction(Request $request)
    {
        $cashinFacade = $this->get('somtel_pip.cashin_facade');

        // Get request body
        $body = $request->getContent();
        
        if (empty($body)) 
        {
            return $this->returnValidationError(['Order should not be empty.']);
        }
        
        // Persist body in DB
        $createdOrder = $cashinFacade->createOrder(json_decode($body, true), $this->getUser());
        
        if ($createdOrder === false) 
        {
            return $this->returnValidationError($cashinFacade->getLastErrors());
        }
        
        return $this->show($createdOrder, $this->detailGroups);
    }

    /**
     * Get a list of cachins
     * 
     * @ApiDoc(
     *     section="Pip",
     *     resource="Pip"
     * )
     */
    public function getCashinsAction()
    {
        /** @var User $user */
        $user = $this->getUser();

        if ($user)
        {
            $orders = $this->getDoctrine()->getRepository('SomtelPipBundle:PipCashinOrder')->getByUser($user);
            return $this->show($orders, $this->listGroups);
        }
        
        return $this->returnValidationError('Invalid user');
    }

    /**
     * Get a single cashin
     *
     * @ApiDoc(
     *     section="Pip",
     *     resource="Pip"
     * )
     *
     * @ParamConverter("barcode", class="SomtelPipBundle:PipCashinOrder", options={
     *     "mapping": {"barcode": "barcode"}
     * })
     *
     * @Security("user == barcode.getUser()")
     */
    public function getCashinAction(PipCashinOrder $barcode)
    {
        return $this->show($barcode, $this->detailGroups);
    }

    private function returnValidationError($errorMessages)
    {
        if (!is_array($errorMessages)) 
        {
            $errorMessages = [$errorMessages];
        }
        
        return $this->show(
            ["errors" => $errorMessages],
            null,
            Util\Codes::HTTP_UNPROCESSABLE_ENTITY
        );
    }
}
