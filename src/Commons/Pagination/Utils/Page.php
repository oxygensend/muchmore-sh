<?php

declare(strict_types=1);

namespace App\Commons\Pagination\Utils;

readonly class Page
{
    public int $currentPage;
    public int $maxPerPage;

    public function __construct(int $page, int $length)
    {
        $this->currentPage = $page;
        $this->maxPerPage = $length;
    }


}