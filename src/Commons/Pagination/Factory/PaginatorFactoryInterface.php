<?php

declare(strict_types=1);

namespace App\Commons\Pagination\Factory;

use App\Commons\Pagination\Paginator\PaginatorInterface;
use App\Commons\Pagination\Utils\Page;
use Doctrine\ORM\QueryBuilder;

interface PaginatorFactoryInterface
{

    public function getDoctrinePaginator(QueryBuilder $qb, Page $page): PaginatorInterface;
}