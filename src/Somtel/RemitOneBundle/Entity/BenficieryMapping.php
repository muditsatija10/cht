<?php

namespace Somtel\RemitOneBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="benificiery_mapping")
 */
class BenficieryMapping
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $cloudBenificieryId;
    
    /**
     * @ORM\Column(type="string", length=100)
     */
    private $remmitBenificieryId;

    /**
     * @ORM\Column(type="string", length=15)
     */
    private $type;


    /**
     * @ORM\Column(type="string", length=15)
     */
    private $agent;



   

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
     * Set email
     *
     * @param string $email
     *
     * @return BenficieryMapping
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set cloudBenificieryId
     *
     * @param string $cloudBenificieryId
     *
     * @return BenficieryMapping
     */
    public function setCloudBenificieryId($cloudBenificieryId)
    {
        $this->cloudBenificieryId = $cloudBenificieryId;

        return $this;
    }

    /**
     * Get cloudBenificieryId
     *
     * @return string
     */
    public function getCloudBenificieryId()
    {
        return $this->cloudBenificieryId;
    }

    /**
     * Set remmitBenificieryId
     *
     * @param string $remmitBenificieryId
     *
     * @return BenficieryMapping
     */
    public function setRemmitBenificieryId($remmitBenificieryId)
    {
        $this->remmitBenificieryId = $remmitBenificieryId;

        return $this;
    }

    /**
     * Get remmitBenificieryId
     *
     * @return string
     */
    public function getRemmitBenificieryId()
    {
        return $this->remmitBenificieryId;
    }

    /**
     * Set type
     *
     * @param string $type
     *
     * @return BenficieryMapping
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set agent
     *
     * @param string $agent
     *
     * @return BenficieryMapping
     */
    public function setAgent($agent)
    {
        $this->agent = $agent;

        return $this;
    }

    /**
     * Get agent
     *
     * @return string
     */
    public function getAgent()
    {
        return $this->agent;
    }
}
