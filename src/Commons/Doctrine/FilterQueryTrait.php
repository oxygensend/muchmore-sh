<?php

declare(strict_types=1);

namespace App\Commons\Doctrine;

use Doctrine\ORM\QueryBuilder;

trait FilterQueryTrait
{

    public function addEqualFilter(string $column, mixed $value, QueryBuilder $qb): void
    {
        if ($value) {
            $paramName = $this->generateParameterName($qb, $column);
            $qb->andWhere($qb->expr()->eq($column, ":$paramName"))
                ->setParameter($paramName, $value);
        }
    }

    public function addInFilter(string $column, ?array $value, QueryBuilder $qb): void
    {
        if (!empty($value)) {
            $paramName = $this->generateParameterName($qb, $column);
            $qb->andWhere($qb->expr()->in($column, ":$paramName"))
                ->setParameter($paramName, $value);
        }
    }

    public function addGtFilter(string $column, mixed $value, QueryBuilder $qb): void
    {
        if ($value) {
            $paramName = $this->generateParameterName($qb, $column);
            $qb->andWhere($qb->expr()->gt($column, ":$paramName"))
                ->setParameter($paramName, $value);
        }
    }


    public function addLtFilter(string $column, mixed $value, QueryBuilder $qb): void
    {
        if ($value) {
            $paramName = $this->generateParameterName($qb, $column);
            $qb->andWhere($qb->expr()->gt($column, ":$paramName"))
                ->setParameter($paramName, $value);
        }
    }

    protected function addSorting(array $sorting, array $columnMappings, QueryBuilder $qb): void
    {
        foreach ($sorting as $sort) {
            if ($sort['column'] && $columnMappings[$sort['column']]) {
                $qb->addOrderBy($columnMappings[$sort['column']], $sort['dir'] ?? 'asc');
            }
        }
    }

    protected function addSearchFilter(array $columns, ?string $search, QueryBuilder $qb): void
    {
        if (!$search) {
            return;
        }

        foreach (explode(' ', $search) as $value) {
            $paramName = $this->generateParameterName($qb, $columns[0]);
            $expressions = [];
            foreach ($columns as $column) {
                $expressions[] = $qb->expr()->like($column, ":$paramName");
            }
            $qb->andWhere($qb->expr()->orX(...$expressions))
                ->setParameter($paramName, "%{$value}%");
        }
    }


    protected function generateParameterName(QueryBuilder $qb, string $column): string
    {
        return str_replace('.', '_', $column . $qb->getParameters()->count()) . 'parameterValue';
    }
}