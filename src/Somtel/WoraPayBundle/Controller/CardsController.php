<?php

namespace Somtel\WoraPayBundle\Controller;

use FOS\RestBundle\Util\Codes;
use ITG\MillBundle\Controller\BaseController;
use Somtel\WoraPayBundle\Form\CreateCardTokenType;
use Somtel\WoraPayBundle\Form\UpdateCardAddressType;
use Somtel\WoraPayBundle\Form\UpdateCardTokenType;
use Somtel\WoraPayBundle\Security\CardTokenVoter;
use Symfony\Component\HttpFoundation\Request;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

class CardsController extends BaseController
{
    /**
     * Update card token
     *
     *
     * @ApiDoc(
     *     section="Cards",
     *     resource="CardToken",
     *     parameters={
     *          {
     *              "name" = "id",
     *              "dataType" = "string",
     *              "required" = true,
     *              "description" = "Card token received from Worapay API"
     *          },
     *          {
     *              "name" = "last4",
     *              "dataType" = "string",
     *              "required" = true,
     *              "description" = "Last 4 numbers of card"
     *          },
     *          {
     *              "name" = "status",
     *              "dataType" = "string",
     *              "required" = true,
     *              "description" = "Status"
     *          }
     *     },
     *     output = {
     *         "class" = "WoraPayBundle\Entity\CardToken",
     *     }
     * )
     */
    public function putCardsAction($id, Request $request)
    {
        if (!$this->isGranted(CardTokenVoter::EDIT, $id)) {
            return $this->show(null, null, Codes::HTTP_FORBIDDEN);
        }

        $form = $this->createPutForm(UpdateCardTokenType::class);
        $form->handleRequest($request);

        if (!$form->isValid()) {
            return $this->show($form->getErrors(), null, 400);
        }

        return $this->show(
            $this->get('wora_pay.card')->updateCard($id, $request->request->all())
        );
    }

    /**
     * Update card address
     *
     *
     * @ApiDoc(
     *     section="Cards",
     *     resource="CardToken",
     *     parameters={
     *          {
     *              "name" = "line1",
     *              "dataType" = "string",
     *              "required" = true,
     *              "description" = "Line 1 of address"
     *          },
     *          {
     *              "name" = "city",
     *              "dataType" = "string",
     *              "required" = true,
     *              "description" = "Name of city"
     *          },
     *          {
     *              "name" = "state",
     *              "dataType" = "string",
     *              "required" = true,
     *              "description" = "Name of state"
     *          },
     *          {
     *              "name" = "country",
     *              "dataType" = "string",
     *              "required" = true,
     *              "description" = "Country code (US, UK ...)"
     *          }
     *     },
     *     output = {
     *         "class" = "WoraPayBundle\Entity\CardToken",
     *     }
     * )
     */
    public function putCardsAddressAction($id, Request $request)
    {
        if (!$this->isGranted(CardTokenVoter::EDIT, $id)) {
            return $this->show(null, null, Codes::HTTP_FORBIDDEN);
        }

        $form = $this->createPutForm(UpdateCardAddressType::class);
        $form->handleRequest($request);

        if (! $form->isValid()) {
            return $this->show($form->getErrors(), null, 400);
        }

        return $this->show(
            $this->get('wora_pay.card')->updateCardAddress($id, $request->request->all())
        );
    }

    /**
     * Remove card token
     *
     *
     * @ApiDoc(
     *     section="Cards",
     *     resource="CardToken",
     *     output = {
     *         "class" = "WoraPayBundle\Entity\CardToken",
     *     }
     * )
     */
    public function deleteCardsAction($id)
    {
        if (!$this->isGranted(CardTokenVoter::DELETE, $id)) {
            return $this->show(null, null, Codes::HTTP_FORBIDDEN);
        }

        if (!$this->get('wora_pay.card')->delete($id)) {
            return $this->error(['success' => false, 'message' => 'Card token not found']);
        }

        return $this->show(['success' => true]);
    }

    /**
     * Get a list of user's cards
     *
     *
     * @ApiDoc(
     *     section="Cards",
     *     resource="CardToken",
     *     output = {
     *         "class" = "WoraPayBundle\Entity\CardToken",
     *     }
     * )
     *
     */
    public function getCardsAction(Request $request)
    {
        // todo: add filtering functionality

        return $this->show(
            $this->get('serializer')->toArray(
                $this->get('wora_pay.card')->getAll([], $this->getUser())
            )
        );
    }

    /**
     * Store new card token
     *
     * @ApiDoc(
     *     section="Cards",
     *     resource="CardToken",
     *     parameters={
     *          {
     *              "name" = "id",
     *              "dataType" = "string",
     *              "required" = true,
     *              "description" = "Card token received from Worapay API"
     *          },
     *          {
     *              "name" = "last4",
     *              "dataType" = "string",
     *              "required" = true,
     *              "description" = "Last 4 numbers of card"
     *          },
     *          {
     *              "name" = "status",
     *              "dataType" = "string",
     *              "required" = true,
     *              "description" = "Status"
     *          },
     *          {
     *              "name" = "status",
     *              "dataType" = "string",
     *              "required" = true,
     *              "description" = "Status"
     *          },
     *          {
     *              "name" = "address_line1",
     *              "dataType" = "string",
     *              "required" = true,
     *              "description" = "Line 1 of address"
     *          },
     *          {
     *              "name" = "address_city",
     *              "dataType" = "string",
     *              "required" = true,
     *              "description" = "Name of city"
     *          },
     *          {
     *              "name" = "address_state",
     *              "dataType" = "string",
     *              "required" = true,
     *              "description" = "Name of state"
     *          },
     *          {
     *              "name" = "address_country",
     *              "dataType" = "string",
     *              "required" = true,
     *              "description" = "Country code (US, UK ...)"
     *          },
     *     },
     *     output = {
     *         "class" = "WoraPayBundle\Entity\CardToken",
     *     }
     * )
     */
    public function postCardsAction(Request $request)
    {
        $form = $this->createPostForm(CreateCardTokenType::class);
        $form->handleRequest($request);

       /* if (!$form->isValid()) {
            return $this->show($form->getErrors(), null, 400);
        }*/
    

        $newToken = $this->get('wora_pay.card')->store(
            $request->request->all(),
            $this->getUser()
        );
        echo $newToken;
        die;

        return $this->show(
            $this->get('serializer')->toArray($newToken)
        );
    }

}
