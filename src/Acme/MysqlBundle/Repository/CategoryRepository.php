<?php

namespace Acme\MysqlBundle\Repository;

/**
 *
 */
class CategoryRepository extends BaseRepository
{
    /**
     *
     */
    public function findAllInOneQueryByRoot($root = 1)
    {
        $qb = $this->createQueryBuilder('c')
            ->select('c')
            ->where('c.root = :root')->setParameter(':root', $root)
            ->addOrderBy('c.lft', 'ASC')
            ->addOrderBy('c.rgt', 'ASC')
        ;

        $result = $this->noCache($qb->getQuery())->getResult();
        if ($result) {
            array_shift($result);
        }

        return $result;
    }

    /**
     *
     */
    public function findOneBySlug($slug)
    {
        $qb = $this->createQueryBuilder('c')
            ->select('c')
            ->where('c.slug = :slug')->setParameter(':slug', $slug)
        ;

        return $this->noCache($qb->getQuery())->getSingleResult();
    }
}
