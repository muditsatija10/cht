<?php

namespace Somtel\WoraPayBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use AppBundle\Entity\User;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * CardToken
 *
 * @ORM\Table(name="card_token")
 * @ORM\Entity(repositoryClass="Somtel\WoraPayBundle\Repository\CardTokenRepository")
 */
class CardToken
{
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
     * @ORM\Column(name="token", type="string", length=255, unique=true)
     */
    private $token;

    /**
     * @var string
     *
     * @ORM\Column(name="last4", type="string", length=4)
     */
    private $last4;

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
    private $cardOwner;

    /**
     * @ORM\OneToOne(targetEntity="Somtel\WoraPayBundle\Entity\Address", cascade={"remove"})
     * @ORM\JoinColumn(name="address_id", referencedColumnName="id")
     */
    private $address;

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
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set token
     *
     * @param string $token
     *
     * @return CardToken
     */
    public function setToken($token)
    {
        $this->token = $token;

        return $this;
    }

    /**
     * Get token
     *
     * @return string
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * Set last4
     *
     * @param string $last4
     *
     * @return CardToken
     */
    public function setLast4($last4)
    {
        $this->last4 = $last4;

        return $this;
    }

    /**
     * Get last4
     *
     * @return string
     */
    public function getLast4()
    {
        return $this->last4;
    }

    /**
     * Set status
     *
     * @param string $status
     *
     * @return CardToken
     */
    public function setStatus($status)
    {
        $this->status = $status;

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
     * Set cardOwner
     *
     * @param \AppBundle\Entity\User $cardOwner
     *
     * @return CardToken
     */
    public function setCardOwner(\AppBundle\Entity\User $cardOwner = null)
    {
        $this->cardOwner = $cardOwner;

        return $this;
    }

    /**
     * Get cardOwner
     *
     * @return \AppBundle\Entity\User
     */
    public function getCardOwner()
    {
        return $this->cardOwner;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return CardToken
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
     * @return CardToken
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
     * Set address
     *
     * @param \Somtel\WoraPayBundle\Entity\Address $address
     *
     * @return CardToken
     */
    public function setAddress(\Somtel\WoraPayBundle\Entity\Address $address = null)
    {
        $this->address = $address;

        return $this;
    }

    /**
     * Get address
     *
     * @return \Somtel\WoraPayBundle\Entity\Address
     */
    public function getAddress()
    {
        return $this->address;
    }
}
