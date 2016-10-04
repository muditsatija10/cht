<?php

namespace Somtel\RemitOneBundle\Service;

class Encrypter
{
    public function __construct($publicKeyPath)
    {
        $this->publicKeyPath = $publicKeyPath;
    }

    public function encrypt($dataToEncrypt)
    {
        $fileContents = file_get_contents($this->publicKeyPath);
        $key = openssl_get_publickey($fileContents);
        $crypted = '';
        openssl_public_encrypt($dataToEncrypt, $crypted, $key);
        return $crypted;
    }


    public function encryptForTransport($dataToEncrypt)
    {
        if (is_array($dataToEncrypt)) {
            $dataToEncrypt = json_encode($dataToEncrypt);
        }
        return base64_encode($this->encrypt($dataToEncrypt));
    }
}
