<?php

namespace ITG\LogBundle\Model;

use ITG\MillBundle\Model\AbstractPaginatedList;
use JMS\Serializer\Annotation as JMS;

class LogList extends AbstractPaginatedList
{
    /**
     * @JMS\Groups({"list"})
     * @JMS\Type("array<AppBundle\Entity\Log>")
     */
    public $result;
}