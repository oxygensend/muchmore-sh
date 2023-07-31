<?php

declare(strict_types=1);

namespace App\Commons\Pagination\Factory;

use App\Commons\Pagination\Paginator\DoctrinePaginator;
use App\Commons\Pagination\Paginator\PaginatorInterface;
use App\Commons\Pagination\Utils\Page;
use Doctrine\ORM\QueryBuilder;
use Pagerfanta\Doctrine\ORM\QueryAdapter;

class PaginatorFactory implements PaginatorFactoryInterface
{

    public function getDoctrinePaginator(QueryBuilder $qb, Page $page): PaginatorInterface
    {
        return new DoctrinePaginator(new QueryAdapter($qb), $page);
    }
}