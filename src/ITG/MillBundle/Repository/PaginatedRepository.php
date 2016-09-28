<?php

namespace ITG\MillBundle\Repository;

use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Tools\Pagination\Paginator;

abstract class PaginatedRepository extends \Doctrine\ORM\EntityRepository
{
    protected $modelClass;

    /** @var QueryBuilder */
    protected $qb;

    /** @var bool */
    protected $noDelete = false;

    /**
     * List paginated results
     *
     * @param int $limit
     * @param int $offset
     * @param array $order
     * @param array $filter
     * @param string $search
     * @return mixed
     */
    public function listPaginated($limit = 10, $offset = 0, $order = null, $filter = null, $search = null)
    {
        // Query builder injector to override default creation
        if (!$this->qb)
        {
            $this->buildQb();
        }

        $this->where();
        $this->search($search);
        $this->filter($filter);
        $this->order($order);
        $this->offset($offset);
        $this->limit($limit);

        $paginator = new Paginator($this->qb);

        return $this->returnResult($paginator);
    }

    /**
     * Build default Query Builder. t is used as an alias
     */
    protected function buildQb()
    {
        $this->qb = $this->getEntityManager()->getRepository($this->getEntityName())->createQueryBuilder('t');
        $this->qb->select('t');
    }

    /**
     * Add default where statement
     */
    protected function where()
    {
        if ($this->noDelete)
        {
            $this->qb->andWhere('t.deleted IS NULL');
        }
    }

    /**
     * Search results by something
     *
     * @param $search string Search query
     */
    protected function search($search)
    {
        // search entity
        if ($search)
        {
            $fields = $this->_class->getFieldNames();

            $res = "''";
            foreach ($fields as $field)
            {
                $res .= ",'|',COALESCE(t.$field, '')";
            }

            $this->qb->andWhere($this->qb->expr()->like("CONCAT('|'$res)", "'%$search%'"));
        }
    }

    /**
     * Filter results by something
     *
     * @param $filter array Assoc array of key => value pairs
     */
    protected function filter($filter)
    {
        // filter fields
        if ($filter)
        {
            foreach ($filter as $key => $value)
            {
                $this->qb->andWhere($this->qb->expr()->eq("t.$key", $value));
            }
        }
    }

    /**
     * Order by
     *
     * @param $order array Assoc array of key => value pairs
     */
    protected function order($order)
    {
        // order by
        if ($order)
        {
            foreach ($order as $key => $value)
            {
                $this->qb->addOrderBy("t.$key", $value);
            }
        }
        else
        {
            $this->qb->addOrderBy('t.id', 'DESC');
        }
    }

    /**
     * Offset query by
     *
     * @param $offset integer
     */
    protected function offset($offset)
    {
        if ($offset)
        {
            $this->qb->setFirstResult($offset);
        }
    }

    /**
     * Limit query by
     *
     * @param $limit integer
     */
    protected function limit($limit)
    {
        if ($limit)
        {
            $this->qb->setMaxResults($limit);
        }
    }

    /**
     * A class to return by the paginated repository
     *
     * @param Paginator $paginator
     * @return mixed
     */
    protected function returnResult(Paginator $paginator)
    {
        return new $this->modelClass($paginator->count(), $paginator->getQuery()->getResult());
    }
}