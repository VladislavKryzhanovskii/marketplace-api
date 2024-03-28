<?php

namespace App\Service\Post;

use Doctrine\Common\Collections\Criteria;
use Symfony\Component\HttpFoundation\RequestStack;

class PaginatorConditionBuilder
{
    private const FILTER_KEY = 'filter';
    private const SORT_KEY = 'sort';

    public function __construct(
        private readonly RequestStack $requestStack,
    )
    {
    }

    public function run(): Criteria
    {
        $request = $this->requestStack->getCurrentRequest();
        $criteria = Criteria::create()
            ->setMaxResults($limit = $request->get('limit', 10))
            ->setFirstResult($limit * ($request->get('page', 1) - 1));

        foreach ($bag = $this->requestStack->getCurrentRequest()->query as $key => $condition) {
            switch (true) {
                case $key === self::FILTER_KEY:
//                    foreach ($bag[$key] as $column)
//                    $criteria->andWhere(
//                        Criteria::expr()->
//                    );
//            }
//        }
//        return $criteria;
    }
}