<?php

namespace ITG\JumioBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;

/**
 * Netverify
 *
 * @ORM\Table(name="netverify")
 * @ORM\Entity(repositoryClass="ITG\JumioBundle\Repository\NetverifyRepository")
 */
class Netverify
{
    const TYPE_PASSPORT = 'PASSPORT';
    const TYPE_DRIVING_LICENSE = 'DRIVING_LICENSE';
    const TYPE_ID_CARD = 'ID_CARD';

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * 
     * @JMS\Groups({"id"})
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="user_reference", type="string", length=255)
     * 
     * @JMS\Groups({"netverify_user_reference"})
     */
    private $userReference;

    /**
     * @var string
     *
     * @ORM\Column(name="photo_front", type="string", length=255, nullable=true)
     * 
     * @JMS\Groups({"netverify_list", "netverify_photo_front"})
     */
    private $photoFront;

    /**
     * @var string
     *
     * @ORM\Column(name="photo_back", type="string", length=255, nullable=true)
     *
     * @JMS\Groups({"netverify_list", "netverify_photo_back"})
     */
    private $photoBack;

    /**
     * @var string
     *
     * @ORM\Column(name="photo_face", type="string", length=255, nullable=true)
     *
     * @JMS\Groups({"netverify_list", "netverify_photo_face"})
     */
    private $photoFace;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=255, nullable=true)
     *
     * @JMS\Groups({"netverify_list", "netverify_type"})
     */
    private $type;

    /**
     * @ORM\Column(name="sent", type="datetime", nullable=true)
     */
    private $sent;

    /**
     * @ORM\ManyToOne(targetEntity="ITG\JumioBundle\Entity\NetverifyResponse")
     *
     * @JMS\Groups({"netverify_list", "netverify_response"})
     */
    private $response;


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
     * Set userReference
     *
     * @param string $userReference
     *
     * @return Netverify
     */
    public function setUserReference($userReference)
    {
        $this->userReference = $userReference;

        return $this;
    }

    /**
     * Get userReference
     *
     * @return string
     */
    public function getUserReference()
    {
        return $this->userReference;
    }

    /**
     * Set photoFront
     *
     * @param string $photoFront
     *
     * @return Netverify
     */
    public function setPhotoFront($photoFront)
    {
        $this->photoFront = $photoFront;

        return $this;
    }

    /**
     * Get photoFront
     *
     * @return string
     */
    public function getPhotoFront()
    {
        return $this->photoFront;
    }

    /**
     * Set photoBack
     *
     * @param string $photoBack
     *
     * @return Netverify
     */
    public function setPhotoBack($photoBack)
    {
        $this->photoBack = $photoBack;

        return $this;
    }

    /**
     * Get photoBack
     *
     * @return string
     */
    public function getPhotoBack()
    {
        return $this->photoBack;
    }

    /**
     * Set photoFace
     *
     * @param string $photoFace
     *
     * @return Netverify
     */
    public function setPhotoFace($photoFace)
    {
        $this->photoFace = $photoFace;

        return $this;
    }

    /**
     * Get photoFace
     *
     * @return string
     */
    public function getPhotoFace()
    {
        return $this->photoFace;
    }

    /**
     * Set type
     *
     * @param string $type
     *
     * @return Netverify
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
     * Set response
     *
     * @param \ITG\JumioBundle\Entity\NetverifyResponse $response
     *
     * @return Netverify
     */
    public function setResponse(\ITG\JumioBundle\Entity\NetverifyResponse $response = null)
    {
        $this->response = $response;

        return $this;
    }

    /**
     * Get response
     *
     * @return \ITG\JumioBundle\Entity\NetverifyResponse
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * Set sent
     *
     * @param \DateTime $sent
     *
     * @return Netverify
     */
    public function setSent($sent)
    {
        $this->sent = $sent;

        return $this;
    }

    /**
     * Get sent
     *
     * @return \DateTime
     */
    public function getSent()
    {
        return $this->sent;
    }
}
