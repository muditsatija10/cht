<?php

namespace AppBundle\Repository;

use AppBundle\Model\SomethingList;
use ITG\MillBundle\Repository\PaginatedRepository;

/**
 * SomethingRepository
 */
class SomethingRepository extends PaginatedRepository
{
    protected $modelClass = SomethingList::class;
}
