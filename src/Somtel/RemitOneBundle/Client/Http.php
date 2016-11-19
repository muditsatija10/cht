<?php

namespace Somtel\RemitOneBundle\Client;

use GuzzleHttp\Client;
use Somtel\RemitOneBundle\Interfaces;
use Somtel\RemitOneBundle\Payload\Status as PayloadStatus;
use Somtel\RemitOneBundle\Service\Decoder;

class Http implements Interfaces\Client\Http
{

    const STATUS_SUCCESS = 'SUCCESS';
    const STATUS_FAILURE = 'FAIL';

    /**
     * @var Client
     */
    private $transporter;

    /**
     * @var Decoder
     */
    private $decoder;

    /**
     * @var Encrypter
     */
    private $encrypter;

    /**
     * @var Payload factory
     */
    private $payloadFactory;

    /**
     * @var Logger
     */
    private $logger;

    public function __construct($transporter, $decoder, $encrypter, $payloadFactory, $logger)
    {
        $this->transporter = $transporter;
        $this->decoder = $decoder;
        $this->encrypter = $encrypter;
        $this->payloadFactory = $payloadFactory;
        $this->logger = $logger;
    }

    /**
     * {@inheritDoc}
     */
    public function post($url, $params)
    {
        return $this->makeRequest($url, [
            "form_params" => $params
        ]);
    }

    /**
     * {@inheritDoc}
     */
    public function postMultipart($url, $params)
    {
        $multipartParams = [];
        foreach ($params as $key => $value){
            $multipartParams[] = [
                "name" => $key,
                "contents" => $value,
            ];
        }
        return $this->makeRequest($url, [
            "multipart" => $multipartParams
        ]);
    }

    public function postEncrypted($url, $params, $dataToEncrypt)
    {
        $encryptedData = $this->encrypter->encryptForTransport($dataToEncrypt);
        $params["encrypted_data"] = $encryptedData;
        return $this->post($url, $params);
    }

    protected function makeRequest($url, $params)
    {
        try {
            $response = $this->transporter->post($url, $params);
            $payload = $this->createResponsePayload($response);
        } catch (\Exception $e) {
            $payload = $this->createExceptionPayload($e);
        }
        $payload->setInput([
            "url" => $url,
            "params" => $params,
        ]);
        return $payload;
    }

    protected function createResponsePayload($response)
    {
        $raw = $response->getBody()->getContents();
       /* echo json_encode($raw);
        die;*/
        $decoded = $this->decoder->decode($raw);
        $payload = $this->payloadFactory->newInstance();

        $output = null;
        $status = PayloadStatus::ACCEPTED;

        if ($decoded["status"] === self::STATUS_SUCCESS) {
            $status = PayloadStatus::SUCCESS;
            $output = isset($decoded["result"]) ? $decoded["result"] : null;
        }

        if ($decoded["status"] === self::STATUS_FAILURE) {
            $status = PayloadStatus::FAILURE;
            $output = $decoded["message"];
        }

        $extras = [
            "responseId" => $decoded["responseId"],
            "status" => $decoded["status"],
            "raw" => $raw,
        ];

        $payload->setStatus($status);
        $payload->setOutput($output);
        $payload->setExtras($extras);
        return $payload;
    }

    protected function createExceptionPayload($exception)
    {
        $payload = $this->payloadFactory->newInstance();
        $payload->setStatus(PayloadStatus::EXCEPTION);
        $payload->setOutput('Exception occured.');
        $payload->setMessages($exception->getMessage());
        $payload->setException($exception);
        $this->logger->error((string) $exception);
        return $payload;
    }

    public function getSeed($request)
    {
        return $this->post('auth/getSeed', [
                "username" => $request["username"]
            ]
        );
    }

    public function login($request)
    {
        $seed = $this->getSeed($request);

        if (! $seed->isSuccess()) {
            return $seed;
        }

        $dataToEncrypt = [
            "seed" => $seed->getOutput()["seed"],
            "password" => $request["password"],
        ];

        return $this->postEncrypted(
            'auth/login',
            ["username" => $request["username"]],
            $dataToEncrypt
        );
    }

    public function loginPin($request)
    {
        $seed = $this->getSeed($request["username"]);

        if (! $seed->isSuccess()) {
            return $seed;
        }

        $dataToEncrypt = [
            "seed" => $seed->getOutput()["seed"],
            "pin" => $request["pin"],
        ];
        return  $this->postEncrypted(
            'auth/loginPin',
            ["username" => $request["username"]],
            $dataToEncrypt
        );
    }

    public function changePassword($request)
    {
        $seed = $this->getSeed($request);
        if (! $seed->isSuccess()) {
            return $seed;
        }
        $dataToEncrypt = [
            "seed" => $seed->getOutput()["seed"],
            "new_password" => $request["new_password"],
        ];
        return $this->postEncrypted(
            'auth/changePassword',
            [
                "email_address" => $request["username"],
                "username" => $request["username"],
                "session_token" => $request["session_token"],
                "forgot_password_token" => $request["forgot_password_token"]
            ],
            $dataToEncrypt
        );
    }


    public function getDestinationCountries($request)
    {
        return $this->post('beneficiary/getDestinationCountries', $request);
    }

    public function getSourceCountries($request)
    {
        return $this->post('remitterUser/getSourceCountries', $request);
    }

    public function createBeneficiary($request)
    {
        return $this->post('beneficiary/createBeneficiary', $request);
    }

    public function listBeneficiaries($request)
    {
        return $this->post('beneficiary/listBeneficiaries', $request);
    }

    public function getBeneficiary($request)
    {
        return $this->post('beneficiary/getBeneficiary', $request);
    }

    public function getRemitterUI($request)
    {
        return $this->post('UISettings/getRemitterUISettings', $request);
    }

    public function registerRemitter($request)
    {
        $dataToEncrypt = [
            "password" => $request["password"],
            "verify_password" => $request["verify_password"],
        ];
        unset($request["password"]);
        unset($request["verify_password"]);

        return $this->postEncrypted(
            'remitterUser/register',
            $request,
            $dataToEncrypt
        );
    }

    public function confirmRegistration($request)
    {
        return $this->post('remitterUser/confirmRegistration', $request);
    }

    public function createTransaction($request)
    {
        return $this->post('transaction/createTransaction', $request);
    }


    public function updateRemitter($request)
    {
        return $this->post('remitterUser/updateProfile', $request);
    }

    public function updateBeneficiary($request)
    {
        return $this->post('beneficiary/updateBeneficiary', $request);
    }

    public function getCharges($request)
    {
        return $this->post('transaction/getCharges', $request);
    }

    public function getRates($request)
    {
        return $this->post('rate/getRates', $request);
    }

    public function getConversionRate($request)
    {
        return $this->post('wallet/calculateMoveFundsBetweenWallets', $request);
    }

    public function listTransactions($request)
    {
        return $this->post('transaction/listTransactions', $request);
    }

    public function getTransaction($request)
    {
        return $this->post('transaction/getTransaction', $request);

    }

    /**
     * @param $request
     * @return object
     */
    public function getTransactionUISettings($request)
    {
        return $this->post('UISettings/getTransactionUISettings', $request);
    }

    public function getWallets($request)
    {
        return $this->post('wallet/getWallets', $request);
    }

    public function forgotPassword($request)
    {
        return $this->post('auth/forgotPassword', $request);
    }

    public function getWalletsBalance($request)
    {
        return $this->post('wallet/getWallets', $request);
    }

    public function getWalletActivity($request)
    {
        return $this->post('wallet/getWalletActivity', $request);
    }

    public function getProfile($request)
    {
        return $this->post('remitterUser/getProfile', $request);
    }

    public function getCollectionPoints($request)
    {
        return $this->post('transaction/getCollectionPoints', $request);
    }
    
}
