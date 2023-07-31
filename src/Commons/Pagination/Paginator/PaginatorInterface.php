<?php

declare(strict_types=1);

namespace App\Commons\Pagination\Paginator;

interface PaginatorInterface extends \Countable
{

    public function getNbResults(): int;

    public function getCurrentPageResults(): iterable;

    public function getLastPage(): int;

    public function getPreviousPage(): ?int;

    public function getNextPage(): ?int;

}