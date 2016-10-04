<?php

namespace ITG\MillBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;

/**
 * @ORM\Embeddable()
 */
class DateRange
{
    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime")
     *
     * @JMS\Groups({"id"})
     * @JMS\Accessor(getter="getStart")
     */
    protected $start;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime")
     *
     * @JMS\Groups({"id"})
     * @JMS\Accessor(getter="getEnd")
     */
    protected $end;


    public function __construct(\DateTime $start = null, \DateTime $end = null)
    {
        $this->setRange($start, $end);
    }

    /**
     * Gets start date. If year is 0000 it is considered not set and null is returned
     *
     * @return \DateTime|null
     */
    public function getStart()
    {
        return $this->start->format('Y') != '0000' ? $this->start : null;
    }

    /**
     * Sets start date of the range. If null is passed, date will be set to 0000-12-12
     *
     * @param \DateTime|null $start
     * @return DateRange
     */
    public function setStart(\DateTime $start = null)
    {
        $this->start = $start === null ? new \DateTime('0000-12-12') : $start;

        return $this;
    }

    /**
     * Gets end date. If year is 9999 it is considered not set and null is returned
     *
     * @return \DateTime|null
     */
    public function getEnd()
    {
        return $this->end->format('Y') != '9999' ? $this->end : null;
    }

    /**
     * Sets start date of the range. If null is passed, date will be set to 9999-12-12
     *
     * @param \DateTime|null $end
     * @return DateRange
     */
    public function setEnd(\DateTime $end = null)
    {
        $this->end = $end === null ? new \DateTime('9999-12-12') : $end;

        return $this;
    }

    /**
     * Sets both start and end dates of the range. If nulls are passed dates are set to 0000-12-12 and 9999-12-12 respectively
     *
     * @param \DateTime|null $start
     * @param \DateTime|null $end
     * @return DateRange
     */
    public function setRange(\DateTime $start = null, \DateTime $end = null)
    {
        $this->start = $start === null ? new \DateTime('0000-12-12') : $start;
        $this->end = $end === null ? new \DateTime('9999-12-12') : $end;

        return $this;
    }

    /**
     * Check if the date range is active now or between provided date.
     *
     * @param \DateTime $date Date to check. Will default to now if not provided
     * @return bool
     */
    public function isActive(\DateTime $date = null)
    {
        if($date === null)
        {
            $date = new \DateTime();
        }

        $ts = $date->getTimestamp();

        return $this->start->getTimestamp() <= $ts && $ts < $this->end->getTimestamp();
    }
}