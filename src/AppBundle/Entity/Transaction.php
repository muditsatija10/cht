<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use AppBundle\Entity\User;
use Gedmo\Mapping\Annotation as Gedmo;
use Somtel\WoraPayBundle\Entity\CardToken;

/**
 * CardToken
 *
 * @ORM\Table(name="transactions")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\TransactionRepository")
 */
class Transaction
{
    const STATUS_INITIATED_ESB = 'INITIATED ESB';
    const STATUS_INITIATED_R1 = 'INITIATED R1';

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="reference", type="string", length=255)
     */
    private $reference;

    /**
     * @ORM\ManyToOne(targetEntity="Somtel\WoraPayBundle\Entity\CardToken")
     * @ORM\JoinColumn(name="card_id", referencedColumnName="id", nullable=true)
     */
    private $cardToken;

    /**
     * @var string
     *
     * @ORM\Column(name="status", type="string", length=255)
     */
    private $status;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $owner;

    /**
     * @var number
     *
     * @ORM\Column(name="account_amount", type="decimal", precision=10, scale=2)
     */
    private $accountAmount;

    /**
     * @var number
     *
     * @ORM\Column(name="cash_collection_amount", type="decimal", precision=10, scale=2)
     */
    private $cashCollectionAmount;

    /**
     * @var number
     *
     * @ORM\Column(name="card_amount", type="decimal", precision=10, scale=2)
     */
    private $cardAmount;

    /**
     * @var number
     *
     * @ORM\Column(name="home_delivery_amount", type="decimal", precision=10, scale=2)
     */
    private $homeDeliveryAmount;

    /**
     * @var number
     *
     * @ORM\Column(name="utility_bill_amount", type="decimal", precision=10, scale=2)
     */
    private $utilityBillAmount;

    /**
     * @var number
     *
     * @ORM\Column(name="mobile_transfer_amount", type="decimal", precision=10, scale=2)
     */
    private $mobileTransferAmount;

    /**
     * @var number
     *
     * @ORM\Column(name="wallet_transfer_amount", type="decimal", precision=10, scale=2)
     */
    private $walletTransferAmount;

    /**
     * @Gedmo\Timestampable(on="create")
     * @Doctrine\ORM\Mapping\Column(name="created_at", type="datetime")
     */
    private $createdAt;

    /**
     * @Gedmo\Timestampable(on="update")
     * @Doctrine\ORM\Mapping\Column(name="updated_at", type="datetime")
     */
    private $updatedAt;

    /**
     * @var string
     *
     * @ORM\Column(name="error_message", type="string", length=5000, nullable=true)
     */
    private $errorMessage;

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set reference
     *
     * @param string $reference
     *
     * @return Transaction
     */
    public function setReference($reference)
    {
        $this->reference = $reference;

        return $this;
    }

    /**
     * Get reference
     *
     * @return string
     */
    public function getReference()
    {
        return $this->reference;
    }

    /**
     * Set status
     *
     * @param string $status
     *
     * @return Transaction
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get accountAmount
     *
     * @return float
     */
    public function getAccountAmount()
    {
        return $this->accountAmount;
    }

    /**
     * Set accountAmount
     *
     * @param float $accountAmount
     *
     * @return Transaction
     */
    public function setAccountAmount($accountAmount)
    {
        $this->accountAmount = $accountAmount;

        return $this;
    }

    /**
     * Get status
     *
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return Transaction
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     *
     * @return Transaction
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * Set cardToken
     *
     * @param CardToken $cardToken
     *
     * @return Transaction
     */
    public function setCardToken(CardToken $cardToken = null)
    {
        $this->cardToken = $cardToken;

        return $this;
    }

    /**
     * Get cardToken
     *
     * @return CardToken
     */
    public function getCardToken()
    {
        return $this->cardToken;
    }

    /**
     * Set owner
     *
     * @param \AppBundle\Entity\User $owner
     *
     * @return Transaction
     */
    public function setOwner(\AppBundle\Entity\User $owner = null)
    {
        $this->owner = $owner;

        return $this;
    }

    /**
     * Get owner
     *
     * @return \AppBundle\Entity\User
     */
    public function getOwner()
    {
        return $this->owner;
    }

    /**
     * Set cashCollectionAmount
     *
     * @param string $cashCollectionAmount
     *
     * @return Transaction
     */
    public function setCashCollectionAmount($cashCollectionAmount)
    {
        $this->cashCollectionAmount = $cashCollectionAmount;

        return $this;
    }

    /**
     * Get cashCollectionAmount
     *
     * @return string
     */
    public function getCashCollectionAmount()
    {
        return $this->cashCollectionAmount;
    }

    /**
     * Set cardAmount
     *
     * @param string $cardAmount
     *
     * @return Transaction
     */
    public function setCardAmount($cardAmount)
    {
        $this->cardAmount = $cardAmount;

        return $this;
    }

    /**
     * Get cardAmount
     *
     * @return string
     */
    public function getCardAmount()
    {
        return $this->cardAmount;
    }

    /**
     * Set homeDeliveryAmount
     *
     * @param string $homeDeliveryAmount
     *
     * @return Transaction
     */
    public function setHomeDeliveryAmount($homeDeliveryAmount)
    {
        $this->homeDeliveryAmount = $homeDeliveryAmount;

        return $this;
    }

    /**
     * Get homeDeliveryAmount
     *
     * @return string
     */
    public function getHomeDeliveryAmount()
    {
        return $this->homeDeliveryAmount;
    }

    /**
     * Set utilityBillAmount
     *
     * @param string $utilityBillAmount
     *
     * @return Transaction
     */
    public function setUtilityBillAmount($utilityBillAmount)
    {
        $this->utilityBillAmount = $utilityBillAmount;

        return $this;
    }

    /**
     * Get utilityBillAmount
     *
     * @return string
     */
    public function getUtilityBillAmount()
    {
        return $this->utilityBillAmount;
    }

    /**
     * Set mobileTransferAmount
     *
     * @param string $mobileTransferAmount
     *
     * @return Transaction
     */
    public function setMobileTransferAmount($mobileTransferAmount)
    {
        $this->mobileTransferAmount = $mobileTransferAmount;

        return $this;
    }

    /**
     * Get mobileTransferAmount
     *
     * @return string
     */
    public function getMobileTransferAmount()
    {
        return $this->mobileTransferAmount;
    }

    /**
     * Set walletTransferAmount
     *
     * @param string $walletTransferAmount
     *
     * @return Transaction
     */
    public function setWalletTransferAmount($walletTransferAmount)
    {
        $this->walletTransferAmount = $walletTransferAmount;

        return $this;
    }

    /**
     * Get walletTransferAmount
     *
     * @return string
     */
    public function getWalletTransferAmount()
    {
        return $this->walletTransferAmount;
    }

    /**
     * Set errorMessage
     *
     * @param string $errorMessage
     *
     * @return Transaction
     */
    public function setErrorMessage($errorMessage)
    {
        $this->errorMessage = $errorMessage;

        return $this;
    }

    /**
     * Get errorMessage
     *
     * @return string
     */
    public function getErrorMessage()
    {
        return $this->errorMessage;
    }
}
