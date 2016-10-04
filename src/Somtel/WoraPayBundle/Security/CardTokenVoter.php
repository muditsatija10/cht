<?php

namespace Somtel\WoraPayBundle\Security;

use AppBundle\Entity\User;
use Doctrine\ORM\EntityManager;
use Somtel\WoraPayBundle\Entity\CardToken;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class CardTokenVoter extends Voter
{
    // todo: can be edited. This constants for future use and as an example
    const VIEW = 'view';
    const EDIT = 'edit';
    const DELETE = 'delete';

    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * CardService constructor.
     * @param $enitytManager EntityManager
     */
    public function __construct($enitytManager)
    {
        $this->entityManager = $enitytManager;
    }

    public function vote(TokenInterface $token, $subject, array $attributes)
    {
        return $this->voteOnAttribute($attributes, $subject, $token);
    }

    protected function supports($attribute, $subject)
    {
        // if the attribute isn't one we support, return false
        if (!in_array($attribute, array(self::VIEW, self::EDIT, self::DELETE))) {
            return false;
        }

        // only vote on Post objects inside this voter
        if (!$subject instanceof CardToken) {
            return false;
        }

        return true;
    }

    /**
     * Perform a single access check operation on a given attribute, subject and token.
     *
     * @param string         $attribute
     * @param mixed          $subject
     * @param TokenInterface $token
     *
     * @return bool
     */
    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        $user = $token->getUser();

        if (!$user instanceof User) {
            // the user must be logged in; if not, deny access
            return false;
        }

        if ($subject instanceof CardToken) {
            $cardToken = $subject;
        } else {
            $cardToken = $this->entityManager->getRepository(CardToken::class)->find($subject);
        }

        // if we can't find CardToken
        if (!$cardToken) {
            return false;
        }

        if ($cardToken->getCardOwner() === $token->getUser()) {
            return true;
        }

        return false;
    }
}