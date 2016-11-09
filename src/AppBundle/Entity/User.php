<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * User
 *
 * @ORM\Table(name="user")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\UserRepository")
 */
class User extends \ITG\UserBundle\Entity\User
{
    /**
     * @var string
     *
     * @ORM\Column(name="is_fully_registered", type="boolean")
     */
    protected $isFullyRegistered = false;

    /**
     * @var string
     *
     * @ORM\Column(name="is_id_verified", type="boolean")
     */
    protected $isIdVerified = false;

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @param $isFullyRegistered
     * @return $this
     */
    public function setIsFullyRegistered($isFullyRegistered)
    {
        $this->isFullyRegistered = (bool) $isFullyRegistered;

        return $this;
    }

    /**
     * @return string
     */
    public function getIsFullyRegistered()
    {
        return $this->isFullyRegistered;
    }

    /**
     * @param $isIdVerified
     * @return $this
     */
    public function setIsIdVerified($isIdVerified)
    {
        $this->isIdVerified = (bool) $isIdVerified;

        return $this;
    }

    /**
     * @return string
     */
    public function getIsIdVerified()
    {
        return $this->isIdVerified;
    }

}
