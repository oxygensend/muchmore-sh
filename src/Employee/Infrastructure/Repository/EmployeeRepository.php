<?php

namespace App\Employee\Infrastructure\Repository;

use App\Commons\Doctrine\FilterQueryTrait;
use App\Employee\Application\Filter\EmployeeFilter;
use App\Employee\Application\Dto\EmployeeDto;
use App\Employee\Domain\Entity\Employee;
use App\Employee\Domain\Repository\EmployeeRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

class EmployeeRepository extends ServiceEntityRepository implements EmployeeRepositoryInterface
{
    use FilterQueryTrait;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Employee::class);
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function upgradePassword(PasswordAuthenticatedUserInterface $user, string $newHashedPassword): void
    {
        if (!$user instanceof Employee) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', $user::class));
        }

        $user->setPassword($newHashedPassword);
        $this->getEntityManager()->persist($user);
        $this->getEntityManager()->flush();
    }

    public function findAllQueryBuilder(?EmployeeFilter $filter, array $sorting): QueryBuilder
    {
        $qb = $this->createQueryBuilder('e');
        $qb->addSelect(
            sprintf(
                "new %s(e.id, e.name, e.surname, e.email, e.pesel, e.birthDate, g.name)",
                EmployeeDto::class
            )
        );

        $qb->leftJoin('e.gender', 'g');

        $this->addEqualFilter('e.name', $filter->name, $qb);
        $this->addEqualFilter('e.surname', $filter->surname, $qb);
        $this->addEqualFilter('e.email', $filter->email, $qb);

        $this->addInFilter('e.id', $filter->ids, $qb);
        $this->addInFilter('g.id', $filter->genderIds, $qb);

        $this->addGtFilter('e.brithDate', $filter->birthDateGt, $qb);
        $this->addLtFilter('e.brithDate', $filter->birthDateLt, $qb);

        $this->addSearchFilter(['e.name', 'e.surname', 'e.email', 'e.pesel'], $filter->search, $qb);

        $this->addSorting($sorting, [
            'name' => 'e.name',
            'surname' => 'e.surname',
            'email' => 'e.email',
            'birthDate' => 'e.birthDate',
            'gender' => 'g.name'
        ], $qb);


        return $qb;
    }

    public function findByEmail(string $email): ?Employee
    {
        return $this->findOneBy(['email' => $email]);
    }
}
