<?php

namespace App\Util\Paginator;

use Doctrine\ORM\QueryBuilder;
use Symfony\Component\HttpFoundation\RequestStack;

readonly class RequestConditionQueryWrapper
{
    public function __construct(
        private RequestStack $requestStack,
    )
    {
    }


    public function wrap(QueryBuilder $queryBuilder): QueryBuilder
    {
        $request = $this->requestStack->getCurrentRequest();
        $alias = current($queryBuilder->getRootAliases());
        $expr = $queryBuilder->expr();


        $queryBuilder
            ->setMaxResults($limit = $request->get('limit', 10))
            ->setFirstResult($limit * ($request->get('page', 1) - 1));


        if (empty($sort = $request->get('sort', []))) {
            $queryBuilder->orderBy(sprintf('%s.updatedAt', $alias), 'DESC');
        } else {
            foreach ($sort as $column => $ordering) {
                $queryBuilder->orderBy(sprintf('%s.%s', $alias, $column), $ordering);
            }
        }

        if (!empty($filter = $request->get('filter', []))) {
            foreach ($filter as $column => $filtering) {
                foreach ($filtering as $method => $value) {
                    if (method_exists($expr, $method)) {
                        $queryBuilder->andWhere($expr->$method(sprintf('%s.%s', $alias, $column), $value));
                    }
                }
            }
        }


        return $queryBuilder;
    }
}