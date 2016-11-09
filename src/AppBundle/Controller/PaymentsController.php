<?php

namespace AppBundle\Controller;

use AppBundle\Pheanstalk\PaymentJob;
use AppBundle\Security\TransactionVerificationVoter;
use FOS\RestBundle\Controller\Annotations\Post;
use AppBundle\Security\TransactionVoter;
use ITG\MillBundle\Controller\BaseController;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use FOS\RestBundle\Util\Codes;
use AppBundle\Form\CreatePaymentType;
use AppBundle\Entity\Transaction;
use Somtel\RemitOneBundle\Payload\Factory\ResponsePayloadFactory;
use Somtel\RemitOneBundle\Payload\Status as PayloadStatus;
use Somtel\WoraPayBundle\Security\CardTokenVoter;
use Symfony\Component\HttpFoundation\Request;
use RemitONE\RemitterWS as RemitterSDK;

class PaymentsController extends BaseController
{
    /**
     * Get a single payment to check status
     *
     * @ApiDoc(
     *     section="Payment",
     *     resource="Transaction",
     *     input = {
     *         "name"="",
     *         "class"="AppBundle\Form\GetPaymentType"
     *     },
     *     output = {
     *         "name" = "",
     *         "class" = "AppBundle\Entity\Transaction",
     *         "groups" = {"id", "something_list", "something_detail"}
     *     }
     * )
     *
     */
    public function getPaymentAction(Transaction $transaction, Request $request)
    {
        if (!$this->isGranted(TransactionVoter::VIEW, $transaction)) {
            return $this->show(null, null, Codes::HTTP_FORBIDDEN);
        }

        $r1Service = $this->get('r1.remitter_service');

        $response = $r1Service->getTransaction(
            [
                'username' => $request->get('username'),
                'session_token' => $request->get('session_token'),
                'trans_ref' => $transaction->getReference()
            ]
        );

        if ($response->isFailure()) {
            return $this->show($response, null, 500);
        }


        if ($response->getOutput()['transaction']['status'] != $transaction->getStatus()) {
            $em = $this->getDoctrine()->getManager();

            $transaction->setStatus($response->getOutput()['transaction']['status']);

            $em->persist($transaction);
            $em->flush();
        }

        return $this->show($transaction);
    }

    /**
     * Create new payment
     *
     * @ApiDoc(
     *     section="Payment",
     *     input = {
     *         "class" = "AppBundle\Form\CreatePaymentType",
     *         "name" = ""
     *     }
     * )
     *  @Post("/payments")
     */
    public function postPaymentAction(Request $request)
    {
        $form = $this->createPostForm(CreatePaymentType::class);

        $payload = ResponsePayloadFactory::newInstance();

        $form->handleRequest($request);
        if (!$form->isValid()) {
            return $this->show($form, null, 400);
        }

        $formData = $form->getData();

        // check if card can be used by this user
        if (isset($formData["card_token_id"])) {
            if (!$this->isGranted(CardTokenVoter::VIEW, $formData['card_token_id'])) {
                $payload->setStatus(PayloadStatus::FAILURE);
                $payload->setOutput('Wrong card token');
                return $this->show($payload->getForClient(), null, 400);
            }
        }
        // check is user verified and limits not exceeded
        if (!$this->isGranted(TransactionVerificationVoter::CREATE, $formData)) {
            $payload->setStatus(PayloadStatus::FAILURE);
            $payload->setOutput('Need additional verification or annual limits exceeded');
            return $this->show($payload->getForClient(), null, 400);
        }


        $parameters = $request->request->all();
        $parameters["payment_token"] = $request->get('card_token_id');

        $r1Service = $this->get('r1.remitter_service');

        $newTransaction = $r1Service->createTransaction($parameters);
        if (!$newTransaction->isSuccess()) {
            return $this->show($newTransaction->getForClient(), null, 500);
        }

        $trans_session_id = $newTransaction->getOutput()["trans_session_id"];
        $member_id = $newTransaction->getOutput()["remitter_id"];
        $confirmedTransaction = $r1Service->confirmTransaction($newTransaction->getInput()["params"]["form_params"] + $newTransaction->getOutput());

        if (!$confirmedTransaction->isSuccess()) {
            $this->get('logger')->error('Confirm Transaction error');
            return $this->show($confirmedTransaction->getForClient(), null, 500);
        }


        $transaction = $this->get('app.payment')->createTransaction(
            $formData['amount'],
            $formData['source_currency'],
            $formData['destination_currency'],
            null,
            $formData['username'],
            $formData['session_token'],
            $confirmedTransaction->getOutput()["reference_number"],
            $formData['card_token_id']
        );

        if (!$transaction) {
            $payload->setStatus(PayloadStatus::FAILURE);
            $payload->setOutput('Can\'t create transaction');
            return $this->show($payload->getForClient(), null, 400);
        }

        $formData += [
            'transactionId' => $transaction->getId(),
            "trans_session_id" => $trans_session_id,
            "member_id" => $member_id,
        ];

        $job = new PaymentJob(uniqid(), $formData);

        $this->get('app.producer.payment')->put($job);

        $payload->setStatus(PayloadStatus::SUCCESS);
        $payload->setOutput([
            "id" => $transaction->getId(),
            "reference" => $transaction->getReference(),
        ]);
        return $this->show($payload, [], Codes::HTTP_CREATED);
    }
}
