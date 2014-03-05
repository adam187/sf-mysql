<?php

namespace Acme\MysqlBundle\Repository;

use Doctrine\ORM\Query;
use Doctrine\ORM\EntityRepository;

class BaseRepository extends EntityRepository
{
    protected function noCache(Query $query)
    {
        $query->setHint(Query::HINT_CUSTOM_OUTPUT_WALKER, 'DoctrineExtensions\Query\MysqlWalker');
        $query->setHint('mysqlWalker.sqlNoCache', true);

        return $query;
    }
}
