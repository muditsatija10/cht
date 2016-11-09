<?php

namespace AppBundle\Security;

use AppBundle\Entity\Transaction;
use AppBundle\Entity\User;
use AppBundle\Service\PaymentService;
use Doctrine\ORM\EntityManager;
use Somtel\RemitOneBundle\Service\RemitterService;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;

class TransactionVerificationVoter extends Voter
{
    const CREATE = 'create';

    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * @var PaymentService
     */
    private $paymentService;

    /**
     * CardService constructor.
     * @param $entityManager EntityManager
     * @param $paymentService PaymentService
     */
    public function __construct($entityManager, $paymentService)
    {
        $this->entityManager = $entityManager;
        $this->paymentService = $paymentService;
    }

    protected function supports($attribute, $subject)
    {
        // if the attribute isn't one we support, return false
        if (!in_array($attribute, array(self::CREATE))) {
            return false;
        }

        if (empty($subject['session_token']) ||
            empty($subject['amount']) ||
            empty($subject['username']) ||
            empty($subject['source_currency'])
        ) {
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

        /**
         * verification logic
         */
        if (!$user->getIsFullyRegistered()) {
            return false;
        }

        $currentAmountInGBP = $subject['amount'];

        if ($subject['source_currency'] !== 'GBP') {
            $currentAmountInGBP = $this->paymentService->calculateAmounts(
                $subject['amount'],
                $subject['source_currency'],
                'GBP',
                null,
                $subject['username'],
                $subject['session_token']
            );

            if (!$currentAmountInGBP) {
                return false;
            }
        }

        $fromDate = new \DateTime();
        $toDate = new \DateTime();

        $fromDate = $fromDate->modify('-1 year')->format('Y-m-d');
        $toDate = $toDate->modify('+1 day')->format('Y-m-d');

        $queryBuilder = $this->entityManager->createQueryBuilder();

        $sumResult = $queryBuilder->select('SUM(t.accountAmount)')
            ->from('AppBundle:Transaction', 't')
            ->where('t.owner = :ownerId')
            ->andWhere($queryBuilder->expr()->notIn('t.status', ':statuses'))
            ->andWhere($queryBuilder->expr()->between('t.createdAt', ':fromDate', ':toDate'))
            ->setParameter(':ownerId', $user->getId())
            ->setParameter(':statuses', ['failed'])
            ->setParameter(':fromDate', $fromDate)
            ->setParameter(':toDate', $toDate)
            ->getQuery()
            ->getResult();

        $totalWithCurrentAmount = isset($currentAmountInGBP['account']) ? $currentAmountInGBP['account'] : $currentAmountInGBP;

        /**
         * get sum of current amount and total by all transaction by prev year (in GBP)
         */
        if (isset($sumResult[0][1])) {
            $totalWithCurrentAmount += $sumResult[0][1];
        }

        if ($totalWithCurrentAmount <= 100) {
            return true;
        }

        if ($totalWithCurrentAmount > 100 && $totalWithCurrentAmount <= 1500) {
            if ($user->getIsIdVerified()) {
                return true;
            }
        }

        if ($totalWithCurrentAmount > 1500) {
            // fixme: implement this rule
        }

        return false;
    }
}