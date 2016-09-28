<?php
/*
 * Provides logic for communicating with merchants.pip-in.net API.
 */

namespace Somtel\PipBundle\Service;

use GuzzleHttp;

class Transporter
{
    protected $httpClient;

    protected $baseUrl;
    protected $merchantUsername;
    protected $merchantPassword;
    protected $merchantSecret;

    public $lastRequest;

    // Set to true at login() method.
    public $isLoggedIn = false;

    public function __construct(
        $httpClient,
        $merchantUsername,
        $merchantPassword,
        $merchantSecret = null,
        $baseUrl = null
    ) {
        $this->httpClient = $httpClient;
        $this->merchantUsername = $merchantUsername;
        $this->merchantPassword = $merchantPassword;
        $this->merchantSecret = $merchantSecret;
        $this->baseUrl = $baseUrl;
    }


    /**
     * Overrides httpClient.
     * Useful while testing.
     *
     * @param object $httpClient
     * @returns object self
     */
    public function setHttpClient($httpClient)
    {
        $this->httpClient = $httpClient;
        return $this;
    }

    /*
     * Login into pip-it.net merchant account with provided credentials.
     *
     * In case no credentials are provided - login with those provided in constructor.
     * Constructor credentials are injected by symfony service container.
     *
     * @param $merchantUsername string Merchant's username.
     * @param $merchantPassword string Merchant's password.
     * @return object self Returns self.
     */
    public function login($merchantUsername = null, $merchantPassword = null)
    {

        if ($this->isLoggedIn) {
            return $this;
        }

        $username = $this->merchantUsername;
        $password = $this->merchantPassword;

        if ($merchantUsername !== null) {
            $username = $merchantUsername;
            $password = $merchantPassword;
        }

        $response = $this->httpClient->request('POST', '/login', [
            "form_params" => [
                "username" => $username,
                "password" => $password,
            ]
        ]);
        $this->lastResponse = $response;

        $this->isLoggedIn = true;

        return $this;

    }

    public function createOrder($cashinOrder)
    {
        $params = [
            "json" => $cashinOrder
        ];
        $response = $this->makeRequest('POST', '/api/order', $params);

        $body = $this->decodeResponse($response);
        if ($response->getStatusCode() === 201) {
            $createdOrder = array_merge($cashinOrder, $body);
            return $createdOrder;
        }
        return false;
    }

    public function getPendingOrders()
    {
        $response = $this->makeRequest('GET', '/api/merchant/orders/pending');
        $body = $this->decodeResponse($response);
        return $body;
    }

    public function getPaidOrders()
    {
        $response = $this->makeRequest('GET', '/api/merchant/orders/paid');
        $body = $this->decodeResponse($response);
        return $body;
    }

    public function getExpiredOrders()
    {
        $response = $this->makeRequest('GET', '/api/merchant/orders/expired');
        $body = $this->decodeResponse($response);
        return $body;
    }

    public function getOrder($barcode)
    {
        $url =  '/api/merchant/order/'.$barcode.'/details';
        $response = $this->makeRequest('GET', $url);
        $body = $this->decodeResponse($response);
        return $body;
    }

    public function makeRequest($method, $uri, $params = [])
    {
        $this->login();
        $response = $this->httpClient->request($method, $uri, $params);
        $this->lastResponse = $response;
        return $response;
    }


    public function decodeResponse(GuzzleHttp\Psr7\Response $response)
    {
        if ($response->hasHeader('Content-Type')) {
            $contentType = $response->getHeader('Content-Type');
            $contentType = implode(';', $contentType);
            if (strpos($contentType, 'json') >= 0) {
                return json_decode((string)$response->getBody(), true);
            }
        }
        return (string)$response->getBody();
    }

    public function getLastErrors()
    {
        $rsp = $this->lastResponse;
        if ($rsp->getStatusCode() < 400) {
            return null;
        }
        $errorBody = $this->decodeResponse($rsp);
        $errors = [];
        foreach ($errorBody["fieldErrors"] as $error) {
            $errors[] = $error["message"];
        }
        return $errors;
    }
}
