<?php

namespace ITG\JumioBundle\Util;

class JumioService
{
    private $itg_jumio;

    /** @var \GuzzleHttp\Client */
    private $http_client;

    public function __construct($itg_jumio, $http_client)
    {
        $this->itg_jumio = $itg_jumio;
        $this->http_client = $http_client;
    }

    public function performNetverify(NetverifyObject $object)
    {
        $c = $this->http_client;

        $response = $c->request('POST', $this->getUrl(), [
            'headers' => [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',

                'User-Agent' => $this->getUserAgent(),
                'Authorization' => $this->getAuthorization(),
            ],
            'body' => $this->getBody($object)
        ]);

        return $response;
    }
    
    private function getUrl()
    {
        return $this->itg_jumio['url'] . '/api/netverify/v2/performNetverify';
    }

    private function getUserAgent()
    {
        // TODO: implement into config
        return 'YOURCOMPANYNAME YOURCOMPANYAPPLICATIONNAME/VERSION';
    }

    private function getAuthorization()
    {
        $encoded = base64_encode(
            $this->itg_jumio['token']
            . ':'
            . $this->itg_jumio['secret']
        );

        return "Basic $encoded";
    }

    private function getBody(NetverifyObject $object)
    {
        $body = $object->buildJson();

        // TODO: implement callback url
        // TODO: implement callback granularity
        //$body['callbackUrl'] = $this->itg_jumio['callback'];

        return $body;
    }
}