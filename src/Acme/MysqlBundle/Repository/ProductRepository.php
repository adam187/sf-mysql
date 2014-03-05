<?php

namespace Acme\MysqlBundle\Repository;

/**
 *
 */
class ProductRepository extends BaseRepository
{
    /**
     *
     */
    public function countForCategories()
    {
        $qb = $this->createQueryBuilder('p')
            ->select('p.category, COUNT(p.category)')
            ->groupBy('p.category')
        ;

        $query = $qb->getQuery();

        $result = $this->noCache($query)->getResult();

        $return = array();

        return $return;
    }

    /**
     *
     */
    public function getProductsFromCategory($category, $offset = 0, $limit = 20)
    {
        $qb = $this->createQueryBuilder('p')
            ->select('p')
            ->where('p.category = :category')->setParameter(':category', $category->getId())
            ->orderBy('p.position', 'ASC')
        ;

        $query = $qb->getQuery();
        $query->setFirstResult($offset);
        $query->setMaxResults($limit);

        return $this->noCache($query)->getResult();
    }

    /**
     *
     */
    public function findOneBySlug($slug)
    {
        $qb = $this->createQueryBuilder('p')
            ->select('p')
            ->where('p.slug = :slug')->setParameter(':slug', $slug)
        ;

        return $this->noCache($qb->getQuery())->getSingleResult();
    }
}
