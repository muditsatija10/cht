<?php
/*
 * Remitter service.
 * Here should be all the business logic related to remitOne remitters.
 */
namespace Somtel\RemitOneBundle\Service;

use Somtel\RemitOneBundle\Client\Http;
use Aura\Payload\PayloadFactory;
use Somtel\RemitOneBundle\Interfaces;
use Somtel\RemitOneBundle\Payload\Status;

class RemitterService
{
    /**
     * @var Interfaces\Client\Http|Http
     */
    protected $httpClient;

    /**
     * @var Interfaces\CurrencyGetter;
     */
    protected $currencyService;

    /**
     * @var PayloadFactory
     */
    private $payloadFactory;

    public function __construct(
        Interfaces\Client\Http $httpClient,
        Interfaces\CurrencyGetter $currencyService,
        $payloadFactory
    ) {
        $this->httpClient = $httpClient;
        $this->currencyService = $currencyService;
        $this->payloadFactory = $payloadFactory;
    }

    /**
     * Logins user into remitOne service. Returns session token, that must be passed in for later calls.
     * @return string Session token.
     */
    public function login($request)
    {
        $response = $this->httpClient->login($request);
        return $response;
    }

    public function loginPin($request)
    {
        $response = $this->httpClient->login($request);
        return $response;
    }

    public function getWalletsBalance($request)
    {
        $response = $this->httpClient->getWalletsBalance($request);
        return $response;
    }


    public function getWallets($request)
    {
        $response = $this->httpClient->getWallets($request);
        return $response;
    }

    public function getprofile($request)
    {
        $response = $this->httpClient->getprofile($request);
        return $response;
    }

    public function getWalletActivity($request)
    {
        $response = $this->httpClient->getWalletActivity($request);
        return $response;
    }


    public function changePassword($request)
    {
        $response = $this->httpClient->changePassword($request);
        return $response;
    }


    /*
     * Retrieve available destination countries array
    */
    public function getDestinationCountries($request)
    {
        $response = $this->httpClient->getDestinationCountries($request);
        return $response;
    }

    /*
    * Retrieve available source countries array
    */
    public function getSourceCountries($request)
    {
        $response = $this->httpClient->getSourceCountries($request);
        return $response;
    }

    /**
     * Retrieve destination currencies based on destination countries.
     */
    public function getDestinationCurrencies($request)
    {
        $destinationCountries = $this->getDestinationCountries($request);
        if (! $destinationCountries->isSuccess()) {
            return $destinationCountries;
        }
        $countryCodes = array_column($destinationCountries->getOutput()["countries"]["country"], 'iso_code');
        $currencies = $this->currencyService->getCurrenciesForCountries($countryCodes);
        $payload = $this->payloadFactory->newInstance();
        $payload->setInput($destinationCountries->getInput());
        $payload->setOutput($currencies);
        $payload->setStatus(Status::SUCCESS);
        return $payload;
    }

    public function registerRemitter($request)
    {
        return $this->httpClient->registerRemitter($request);
    }

    public function updateRemitter($request)
    {
        return $this->httpClient->updateRemitter($request);
    }

    public function updateBeneficiary($request)
    {
        return $this->httpClient->updateBeneficiary($request);
    }

    /*
     * Create new Beneficiary
    */
    public function createBeneficiary($request)
    {
        $response = $this->httpClient->createBeneficiary($request);
        return $response;
    }

    /*
     * Gets beneficiary
     */
    public function getBeneficiary($request)
    {
        $response = $this->httpClient->getBeneficiary($request);
        return $response;
    }

    /*
     * Gets all beneficiaries
     */
    public function listBeneficiaries($request)
    {
        $response = $this->httpClient->listBeneficiaries($request);
        return $response;
    }

    /*
    * sends password link to given email
   */
    public function forgotPassword($request)
    {

        $response = $this->httpClient->forgotPassword($request);
        return $response;
    }


    /*
    * Retrieve fee charge for sending amount
    */
    public function getCharges($request)
    {

        $response = $this->httpClient->getCharges($request);
        return $response;
    }

    /*
     * Retrieve fee charge for sending amount
     */
    public function getRates($request)
    {
        $response = $this->httpClient->getRates($request);
        return $response;
    }

    public function getConversionRate($request)
    {
        $response = $this->httpClient->getConversionRate($request);
        return $response;
    }

    /*
   * Retrieve list of remitter transactions
   */
    public function listTransactions($request)
    {

        $response = $this->httpClient->listTransactions($request);
        return $response;
    }

    /*
    * Retrieve details of remiter transaction
    */
    public function getTransaction($request)
    {
        $response = $this->httpClient->getTransaction($request);
        return $response;
    }

    /*
     * Retrieve details of remiter transaction
     */
    public function getTransactionUISettings($request)
    {
        $response = $this->httpClient->getTransactionUISettings($request);
        return $response;
    }

    public function confirmRegistration($request)
    {
        $response = $this->httpClient->confirmRegistration($request);
        return $response;
    }

    public function createTransaction($request)
    {
        $response = $this->httpClient->createTransaction($request);
        return $response;
    }
    /**
     * @param $request
     * @return mixed
     */
    public function getPaymentMethods($request)
    {
        $response = $this->httpClient->getTransactionUISettings($request);

        $output = $response->getOutput();

        if (!isset($output['transfer_types']['transfer_type'])) {
            return [];
        }

        return $output['transfer_types']['transfer_type'];
    }
}
