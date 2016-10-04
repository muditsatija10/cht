<?php

namespace AppBundle\Model;

use ITG\MillBundle\Model\AbstractPaginatedList;
use JMS\Serializer\Annotation as JMS;

class SomethingList extends AbstractPaginatedList
{
    /**
     * @JMS\Groups({"doc", "something_list"})
     * @JMS\Type("array<AppBundle\Entity\Something>")
     */
    public $result;
}