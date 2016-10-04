<?php

namespace Somtel\RemitOneBundle\Interfaces\Client;

interface Http
{
    /**
     * Post data to endpoint.
     * @var string $url RemitOne endpoint url. Example: wallet/getWallets.
     * @var array $params Associative array of params to post. Where key is field id and value is value.
     *
     * @return object \Aura\Payload_Interface\PayloadInterface Payload object. See https://github.com/auraphp/Aura.Payload for overview.
     */
    public function post($url, $params);

    /**
     * Convert params to multipart format and post data to endpoint.
     * @var string $url RemitOne endpoint url. Example: wallet/getWallets.
     * @var array $params Associative array of params to post. Where key is field id and value is value.
     *
     * @return object \Aura\Payload_Interface\PayloadInterface Payload object. See https://github.com/auraphp/Aura.Payload for overview.
     */
    public function postMultipart($url, $params);
}
