<?php

namespace ITG\UserBundle\Repository;

use ITG\MillBundle\Repository\PaginatedRepository;
use ITG\UserBundle\Model\RoleSetList;

/**
 * RoleSetRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class RoleSetRepository extends PaginatedRepository
{
    protected $modelClass = RoleSetList::class;
}
