<?php

declare(strict_types=1);

namespace App\Employee\Application\Dto;

readonly class EmployeeIdDto
{
    public function __construct(public int $id)
    {
    }

}