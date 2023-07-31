<?php

declare(strict_types=1);

namespace App\Commons\Pagination\Dto;

use App\Commons\Pagination\Paginator\PaginatorInterface;
use App\Employee\Application\Dto\EmployeeDto;
use OpenApi\Attributes as OA;

readonly class PagedListDto
{
    #[OA\Property(type: 'array', items: new OA\Items(type: EmployeeDto::class))]
    public iterable $data;
    public int $totalRecords;
    public int $currentRecords;
    public ?int $previousPage;
    public ?int $nextPage;
    public ?int $lastPage;

    public function __construct(PaginatorInterface $paginator)
    {
        $this->data = $paginator->getCurrentPageResults();
        $this->totalRecords = $paginator->count();
        $this->currentRecords = $paginator->getNbResults();
        $this->previousPage = $paginator->getPreviousPage();
        $this->nextPage = $paginator->getNextPage();
        $this->lastPage = $paginator->getLastPage();
    }

}