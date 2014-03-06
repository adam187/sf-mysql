<?php

namespace Acme\MysqlBundle\Repository;

use Acme\MysqlBundle\Entity\Category;

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

    /**
     *
     */
    public function getAllSubcategories(Category $category)
    {
        $qb = $this->createQueryBuilder('c')
            ->select('c')
            ->where('c.root = :root')->setParameter(':root', $category->getRoot())
            ->andWhere('c.lft > :left')->setParameter(':left', $category->getLeft())
            ->andWhere('c.lft < :right')->setParameter(':right', $category->getRight())
        ;

        return $this->noCache($qb->getQuery())->getResult();
    }

    /**
     *
     */
    public function getAllLeavsIds(Category $category)
    {
        $subcategories = $this->getAllSubcategories($category);
        if ($subcategories) {
            $ids = array();
            foreach($subcategories as $subcategory) {
                if ($subcategory->isLeaf()) {
                    $ids[] = $subcategory->getId();
                }
            }

            return $ids;
        }

        return false;
    }
}
