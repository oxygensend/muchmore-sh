<?php

namespace App\Employee\Infrastructure\Repository;

use App\Employee\Domain\Entity\Sex;
use App\Employee\Domain\Repository\SexRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;


class SexRepository extends ServiceEntityRepository implements SexRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Sex::class);
    }

}
