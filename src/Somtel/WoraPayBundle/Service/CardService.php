<?php

namespace Somtel\WoraPayBundle\Service;

use AppBundle\Entity\User;
use Doctrine\ORM\EntityManager;
use Somtel\WoraPayBundle\Entity\Address;
use Somtel\WoraPayBundle\Entity\CardToken;

class CardService
{
    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * CardService constructor.
     * @param $enitytManager
     */
    public function __construct($enitytManager)
    {
        $this->entityManager = $enitytManager;
    }

    /**
     * @param $tokenData
     * @param $user
     * @return CardToken
     */
    public function store($tokenData, $user)
    {
        $token = new CardToken();
        $address = new Address();

        $address->setLine1($tokenData['address_line1']);
        $address->setLine2('');
        $address->setCity($tokenData['address_city']);

        // fixme: hardcoded - while counties table not exists
        $address->setCountryId(1);

        $address->setState($tokenData['address_state']);

        $token->setToken($tokenData['id']);
        $token->setLast4($tokenData['last4']);
        $token->setStatus($tokenData['status']);
        $token->setAddress($address);
        $token->setCardOwner($user);

        $this->entityManager->persist($address);
        $this->entityManager->persist($token);
        $this->entityManager->flush();

        return $token;
    }

    /**
     * @param $id
     * @param $tokenData
     * @return null|object|CardToken
     */
    public function updateCard($id, $tokenData)
    {
        $token = $this->entityManager->getRepository(CardToken::class)->find($id);

        // update card token data
        $token->setToken($tokenData['id']);
        $token->setLast4($tokenData['last4']);
        $token->setStatus($tokenData['status']);

        $this->entityManager->persist($token);
        $this->entityManager->flush();

        return $token;
    }

    /**
     * @param $id
     * @param $addressData
     * @return null|object|CardToken
     */
    public function updateCardAddress($id, $addressData)
    {
        $token = $this->entityManager->getRepository(CardToken::class)->find($id);

        $address = $token->getAddress();

        $address->setLine1($addressData['line1']);

        // fixme: this field can be ambiguous and can be removed in future
        $address->setLine2('');

        $address->setCity($addressData['city']);
        $address->setCountryId(1);
        $address->setState($addressData['state']);

        $this->entityManager->persist($address);
        $this->entityManager->flush();

        return $token;
    }

    /**
     * @param $id
     * @return bool
     */
    public function delete($id)
    {
        $token = $this->entityManager->getRepository(CardToken::class)->find($id);

        if (!$token) {
            return false;
        }

        $this->entityManager->remove($token);
        $this->entityManager->flush();

        return true;
    }

    /**
     * @param $params
     * @param $user User
     * @return array|CardToken[]
     */
    public function getAll($params, $user)
    {
        $tokens = $this->entityManager->getRepository(CardToken::class)->findBy(['cardOwner' => $user->getId()]);

        return $tokens;
    }
}
