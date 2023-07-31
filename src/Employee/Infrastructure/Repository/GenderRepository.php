<?php

namespace App\Employee\Infrastructure\Repository;

use App\Employee\Domain\Entity\Gender;
use App\Employee\Domain\Exception\GenderNotFoundException;
use App\Employee\Domain\Repository\GenderRepos;
use App\Employee\Domain\Repository\GenderRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;


class GenderRepository extends ServiceEntityRepository implements GenderRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Gender::class);
    }

    public function getGenderOrFail(int $id): Gender
    {
        $gender = $this->find($id);
        if (!$gender) {
            throw new GenderNotFoundException($id);
        }
        return $gender;
    }
}
