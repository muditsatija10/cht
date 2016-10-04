<?php

namespace ITG\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;

class Token
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     *
     * @JMS\Groups({"id"})
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="token", type="guid", unique=true)
     *
     * @JMS\Groups({"token_list", "token_token"})
     */
    protected $token;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="activated", type="datetime", nullable=true)
     *
     * @JMS\Groups({"token_list", "token_activated"})
     */
    protected $activated;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="inactivated", type="datetime", nullable=true)
     *
     * @JMS\Groups({"token_list", "token_inactivated"})
     */
    protected $inactivated;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User")
     * @ORM\JoinColumn(nullable=false)
     *
     * @JMS\Groups({"token_list", "token_user"})
     */
    protected $user;


    /**
     * Get id
     *
     * @return int
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
     * @return Token
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
     * Set activated
     *
     * @param \DateTime $activated
     *
     * @return Token
     */
    public function setActivated($activated)
    {
        $this->activated = $activated;

        return $this;
    }

    /**
     * Get activated
     *
     * @return \DateTime
     */
    public function getActivated()
    {
        return $this->activated;
    }

    /**
     * Set inactivated
     *
     * @param \DateTime $inactivated
     *
     * @return Token
     */
    public function setInactivated($inactivated)
    {
        $this->inactivated = $inactivated;

        return $this;
    }

    /**
     * Get inactivated
     *
     * @return \DateTime
     */
    public function getInactivated()
    {
        return $this->inactivated;
    }

    /**
     * Set user
     *
     * @param \AppBundle\Entity\User $user
     *
     * @return Token
     */
    public function setUser(\ITG\UserBundle\Entity\User $user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \AppBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }
}
