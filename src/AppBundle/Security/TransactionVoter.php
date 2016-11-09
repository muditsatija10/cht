<?php

namespace AppBundle\Security;

use AppBundle\Entity\Transaction;
use AppBundle\Entity\User;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;

class TransactionVoter extends Voter
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
     * @param $entityManager EntityManager
     */
    public function __construct($entityManager)
    {
        $this->entityManager = $entityManager;
    }

    protected function supports($attribute, $subject)
    {
        // if the attribute isn't one we support, return false
        if (!in_array($attribute, array(self::VIEW, self::EDIT, self::DELETE))) {
            return false;
        }

        // only vote on Post objects inside this voter
        if (!$subject instanceof Transaction) {
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

        if ($subject instanceof Transaction) {
            $transaction = $subject;
        } else {
            $transaction = $this->entityManager->getRepository(Transaction::class)->find($subject);
        }

        // if we can't find Transaction
        if (!$transaction) {
            return false;
        }

        if ($transaction->getOwner()->getId() === $token->getUser()->getId()) {
            return true;
        }

        return false;
    }
}