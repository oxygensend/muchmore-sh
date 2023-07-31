<?php

declare(strict_types=1);

namespace App\Employee\Application\Filter;

readonly class EmployeeFilter
{
    public function __construct(
        public ?string    $name = null,
        public ?string    $surname = null,
        public ?string    $email = null,
        public ?string    $pesel = null,
        public ?string    $search = null,
        public ?\DateTime $birthDateLt = null,
        public ?\DateTime $birthDateGt = null,
        public array      $ids = [],
        public array      $genderIds = [],
        public array      $sorting = []
    )
    {
    }

    public static function fromQuery(array $filters): self
    {
        return new self(
            name: $filters['name'] ?? null,
            surname: $filters['surname'] ?? null,
            email: $filters['email'] ?? null,
            pesel: $filters['pesel'] ?? null,
            search: $filters['search'] ?? null,
            birthDateLt: isset($filters['birthDateLt']) ? new \DateTime($filters['birthDateLt']) : null,
            birthDateGt: isset($filters['birthDateGt']) ? new \DateTime($filters['birthDateGt']) : null,
            ids: $filters['ids'] ?? [],
            genderIds: $filters['genderIds'] ?? [],
            sorting: $sorting ?? []
        );
    }

}