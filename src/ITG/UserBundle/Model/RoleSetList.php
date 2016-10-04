<?php

namespace ITG\UserBundle\Model;

use ITG\MillBundle\Model\AbstractPaginatedList;
use JMS\Serializer\Annotation as JMS;

class RoleSetList extends AbstractPaginatedList
{
    /**
     * @JMS\Groups({"list"})
     * @JMS\Type("array<AppBundle\Entity\RoleSet>")
     */
    public $result;
}