<?php
namespace AppBundle\Service;

use AppBundle\Entity\Transaction;
use Aura\Payload_Interface\PayloadStatus;
use Doctrine\ORM\EntityManager;
use Monolog\Logger;
use Somtel\RemitOneBundle\Service\RemitterService;
use Somtel\WoraPayBundle\Entity\CardToken;
use Somtel\WoraPayBundle\Service\ApiService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;

use RemitONE\RemitterWS as RemitterSDK;

class PaymentService
{
    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * @var RemitterService
     */
    private $r1Service;

    /**
     * @var ApiService
     */
    private $wpService;

    /**
     * @var TokenStorage
     */
    private $token;

    /**
     * @var Logger
     */
    private $logger;

    public function __construct($entityManager, $r1Service, $wpService, $token, $logger)
    {
        $this->entityManager = $entityManager;
        $this->r1Service = $r1Service;
        $this->wpService = $wpService;
        $this->token = $token;
        $this->logger = $logger;
    }

    /**
     * @param $amount
     * @param $sourceCurrency
     * @param $destinationCurrency
     * @param $destinationCountry
     * @param $username
     * @param $sessionToken
     * @param $reference
     * @param null $cardTokenId
     * @return Transaction|bool
     */
    public function createTransaction(
        $amount,
        $sourceCurrency,
        $destinationCurrency,
        $destinationCountry,
        $username,
        $sessionToken,
	$reference,
        $cardTokenId = null
    ) {
        $transaction = new Transaction();

        $transaction->setReference($reference);
        $transaction->setStatus(Transaction::STATUS_INITIATED_ESB);
        $transaction->setOwner($this->token->getToken()->getUser());

        if (!is_null($cardTokenId)) {
            $cardToken = $this->entityManager->getReference(CardToken::class, $cardTokenId);
            $transaction->setCardToken($cardToken);
        }

        // fixme: hardcoded values
        $calculatedAmounts = $this->calculateAmounts(
            $amount,
            $sourceCurrency,
            'GBP', //$destinationCurrency,
            'United Kingdom- GBP', //$destinationCountry,
            $username,
            $sessionToken
        );

        if (!$calculatedAmounts) {
            return false;
        }

        $transaction->setAccountAmount($calculatedAmounts['account']);
        $transaction->setCashCollectionAmount($calculatedAmounts['cash_collection']);
        $transaction->setCardAmount($calculatedAmounts['card']);
        $transaction->setHomeDeliveryAmount($calculatedAmounts['home_delivery']);
        $transaction->setUtilityBillAmount($calculatedAmounts['utility_bill']);
        $transaction->setMobileTransferAmount($calculatedAmounts['mobile_transfer']);
        $transaction->setWalletTransferAmount($calculatedAmounts['wallet_transfer']);

        $this->entityManager->persist($transaction);
        $this->entityManager->flush();

        return $transaction;
    }

    /**
     * @param $amount
     * @param $sourceCurrency
     * @param $destinationCurrency
     * @param $destinationCountry
     * @param $username
     * @param $sessionToken
     * @return array|bool
     */
    public function calculateAmounts(
        $amount,
        $sourceCurrency,
        $destinationCurrency,
        $destinationCountry,
        $username,
        $sessionToken
    ) {
        $ratesResponse = $this->r1Service->getRates([
            'username' => $username,
            'session_token' => $sessionToken,
            'destination_country' => $destinationCountry,
            'source_currency' => $sourceCurrency,
            'destination_currency' => $destinationCurrency
        ]);

        if (!$ratesResponse->isSuccess()) {
            return false;
        }

        $rates = $ratesResponse->getOutput()['rates']['rate'];

        /**
         * be sure we have only one set of rates (R1 can return array of arrays of rates)
         */
        $rates = (isset($rates[0]) && is_array($rates[0])) ? $rates[0] : $rates;

        return [
            'account'           => $amount * $rates['account'],
            'cash_collection'   => $amount * $rates['cash_collection'],
            'card'              => $amount * $rates['card'],
            'home_delivery'     => $amount * $rates['home_delivery'],
            'utility_bill'      => $amount * $rates['utility_bill'],
            'mobile_transfer'   => $amount * $rates['mobile_transfer'],
            'wallet_transfer'   => $amount * $rates['wallet_transfer'],
        ];
    }


    /**
     * fixme: need to add deep logging and errors handling
     *
     * @param $params mixed
     * @return bool|\Doctrine\Common\Proxy\Proxy|null|object
     */
    public function createPayment($params)
    {
        $transaction = $this->entityManager->getReference(Transaction::class, $params['transactionId']);

        if (!$transaction) {
            $this->logger->error('Can\'t find transaction is ESB database');
            return false;
        }

        $transaction->setStatus('session created');

        // @TODO: fix hardcoded payment_method == 6;
        if (isset($params["payment_method"]) && $params["payment_method"] == '6') {
            $wpResponse = $this->wpService->charge(
                $transaction->getCardToken()->getToken(),
                $params["amount"],
                $params["source_currency"],
                $transaction->getReference()
            );

            if ($wpResponse->getStatus() !== PayloadStatus::ACCEPTED) {
                $this->logger->error('Charge card error');
                $transaction->setStatus('failed');
                $woraOutput = $wpResponse->getOutput();
                if (isset($woraOutput["description"])) {
                    $transaction->setErrorMessage($wpResponse->getOutput()["description"]);
                }
                $this->entityManager->persist($transaction);
                $this->entityManager->flush();
                return false;
            }
        }

        $params["trans_ref"] = $transaction->getReference();

        try {
            $sdk = new RemitterSDK\RemitterWS();
            $params["security_hash"] = $sdk->transaction->generateSecurityHash([
                "trans_ref" => $transaction->getReference(),
                "member_id" => $params["member_id"],
                "temp_trans_id" => $params["trans_session_id"],
            ]);
        } catch (\Throwable $e) {
            $this->logger->error('Error in generating security hash.', ['exception' => $e]);
            return false;
        }
        $response = $this->r1Service->paymentCleared($params);

        if (!$response->isSuccess()) {
            $this->logger->error('Error in transaction payment clearing', ["response" => $response->getForClient()]);
            return false;
        }

        $transaction->setStatus($response->getOutput()['status']);

        $this->entityManager->persist($transaction);
        $this->entityManager->flush();

        return $transaction;
    }
}
