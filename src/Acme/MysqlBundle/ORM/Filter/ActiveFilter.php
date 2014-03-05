<?php

namespace Acme\MysqlBundle\ORM\Filter;

use Doctrine\ORM\Mapping\ClassMetaData;
use Doctrine\ORM\Query\Filter\SQLFilter;

class ActiveFilter extends SQLFilter
{
    public function addFilterConstraint(ClassMetadata $targetEntity, $targetTableAlias)
    {
        if ($targetEntity->hasField('active')) {
            return $targetTableAlias.'.active = 1';
        }

        return "";
    }
}
