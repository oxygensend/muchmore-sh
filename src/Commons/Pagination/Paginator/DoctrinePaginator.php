<?php

declare(strict_types=1);

namespace App\Commons\Pagination\Paginator;

use App\Commons\Pagination\Utils\Page;
use Pagerfanta\Doctrine\ORM\QueryAdapter;
use Pagerfanta\Pagerfanta;
use Pagerfanta\PagerfantaInterface;

class DoctrinePaginator implements PaginatorInterface
{
    private readonly PagerfantaInterface $pagerfanta;

    public function __construct(QueryAdapter $queryAdapter, Page $page)
    {
        $this->pagerfanta = Pagerfanta::createForCurrentPageWithMaxPerPage(
            $queryAdapter,
            $page->currentPage,
            $page->maxPerPage
        );
    }

    public function count(): int
    {
        return $this->pagerfanta->count();
    }

    public function getNbResults(): int
    {
        return $this->pagerfanta->getNbPages();
    }

    public function getCurrentPageResults(): iterable
    {
        return $this->pagerfanta->getCurrentPageResults();
    }

    public function getLastPage(): int
    {
        return $this->pagerfanta->getNbPages();
    }

    public function getPreviousPage(): ?int
    {
        return $this->pagerfanta->hasPreviousPage() ? $this->pagerfanta->getPreviousPage() : null;
    }

    public function getNextPage(): ?int
    {
        return $this->pagerfanta->hasNextPage() ? $this->pagerfanta->getNextPage() : null;
    }
}