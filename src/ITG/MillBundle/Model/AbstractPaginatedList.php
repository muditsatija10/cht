<?php

namespace ITG\MillBundle\Model;

use JMS\Serializer\Annotation as JMS;

abstract class AbstractPaginatedList
{
    /**
     * Total count of all result roles
     * @JMS\Groups({"list"})
     * @JMS\Type("integer")
     */
    public $count;

    /**
     * Paginated array of result
     * @JMS\Groups({"list"})
     * @JMS\Type("array")
     */
    public $result;

    /**
     * @param $total integer Total returned rows
     * @param $result array Paginated array
     */
    public function __construct($total, $result)
    {
        $this->count = $total;
        $this->result = $result;
    }
}