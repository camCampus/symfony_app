<?php

namespace App\Api;

use ApiPlatform\Doctrine\Orm\Util\QueryNameGeneratorInterface;
use ApiPlatform\Metadata\Operation;
use Doctrine\ORM\QueryBuilder;

class FilterQuacks implements \ApiPlatform\Doctrine\Orm\Extension\QueryItemExtensionInterface
{

    public function applyToItem(QueryBuilder $queryBuilder, QueryNameGeneratorInterface $queryNameGenerator, string $resourceClass, array $identifiers, Operation $operation = null, array $context = []): void
    {
        // TODO: Implement applyToItem() method.
    }
}