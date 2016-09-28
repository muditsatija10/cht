<?php

namespace ITG\LogBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;

class Log
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
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime")
     *
     * @JMS\Groups({"log_list"})
     */
    protected $date;

    /**
     * @var string
     *
     * @ORM\Column(name="request", type="text", nullable=true)
     *
     * @JMS\Groups({"log_details"})
     */
    protected $request;

    /**
     * @var string
     *
     * @ORM\Column(name="response", type="text", nullable=true)
     *
     * @JMS\Groups({"log_details"})
     */
    protected $response;

    /**
     * @var string
     *
     * @ORM\Column(name="project", type="string", length=255, nullable=true)
     *
     * @JMS\Groups({"log_list"})
     */
    protected $project;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=255)
     *
     * @JMS\Groups({"log_list"})
     */
    protected $type = 'LOG';

    /**
     * @var string
     *
     * @ORM\Column(name="payload", type="object", nullable=true)
     *
     * @JMS\Groups({"log_details"})
     */
    protected $payload;

    /**
     * @var string
     *
     * @ORM\Column(name="extra", type="object", nullable=true)
     *
     * @JMS\Groups({"log_details"})
     */
    protected $extra;


    public function __construct()
    {
        $this->date = new \DateTime();
    }

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
     * Set date
     *
     * @param \DateTime $date
     *
     * @return Log
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set request
     *
     * @param string $request
     *
     * @return Log
     */
    public function setRequest($request)
    {
        $this->request = $request;

        return $this;
    }

    /**
     * Get request
     *
     * @return string
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * Set response
     *
     * @param string $response
     *
     * @return Log
     */
    public function setResponse($response)
    {
        $this->response = $response;

        return $this;
    }

    /**
     * Get response
     *
     * @return string
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * Set project
     *
     * @param string $project
     *
     * @return Log
     */
    public function setProject($project)
    {
        $this->project = $project;

        return $this;
    }

    /**
     * Get project
     *
     * @return string
     */
    public function getProject()
    {
        return $this->project;
    }

    /**
     * Set type
     *
     * @param string $type
     *
     * @return Log
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
     * Set payload
     *
     * @param object $payload
     *
     * @return Log
     */
    public function setPayload($payload)
    {
        $this->payload = $payload;

        return $this;
    }

    /**
     * Get payload
     *
     * @return object
     */
    public function getPayload()
    {
        return $this->payload;
    }

    /**
     * Set extra
     *
     * @param object $extra
     *
     * @return Log
     */
    public function setExtra($extra)
    {
        $this->extra = $extra;

        return $this;
    }

    /**
     * Get extra
     *
     * @return object
     */
    public function getExtra()
    {
        return $this->extra;
    }
}

