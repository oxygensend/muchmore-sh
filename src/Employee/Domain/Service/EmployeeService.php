<?php

declare(strict_types=1);

namespace App\Employee\Domain\Service;

use App\Commons\Pagination\Dto\PagedListDto;
use App\Commons\Pagination\Factory\PaginatorFactoryInterface;
use App\Commons\Pagination\Utils\Page;
use App\Employee\Application\Filter\EmployeeFilter;
use App\Employee\Application\Payload\CreateEmployeePayload;
use App\Employee\Application\Payload\UpdateEmployeePayload;
use App\Employee\Application\Dto\EmployeeIdDto;
use App\Employee\Application\Dto\EmployeePagedListResponse;
use App\Employee\Application\Dto\EmployeeDto;
use App\Employee\Domain\Entity\Employee;
use App\Employee\Domain\Exception\EmployeeWithEmailExists;
use App\Employee\Domain\Repository\EmployeeRepositoryInterface;
use App\Employee\Domain\Repository\GenderRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class EmployeeService
{

    public function __construct(
        private readonly GenderRepositoryInterface   $genderRepository,
        private readonly EmployeeRepositoryInterface $employeeRepository,
        private readonly UserPasswordHasherInterface $passwordHasher,
        private readonly EntityManagerInterface      $em,
        private readonly PaginatorFactoryInterface   $paginator

    )
    {
    }

    public function getEmployee(Employee $employee): EmployeeDto
    {
        return EmployeeDto::fromEntity($employee);
    }

    public function createEmployee(CreateEmployeePayload $payload): EmployeeIdDto
    {
        if ($this->employeeRepository->findByEmail($payload->getEmail())) {
            throw new EmployeeWithEmailExists($payload->getEmail());
        }

        $gender = $this->genderRepository->getGenderOrFail($payload->getGenderId());

        $employee = new Employee(
            $payload->getEmail(),
            $payload->getName(),
            $payload->getSurname(),
            $payload->getPesel(),
            $payload->getBirthDate(),
            $gender
        );

        $password = $this->passwordHasher->hashPassword($employee, $payload->getPassword());
        $employee->setPassword($password);

        $this->em->persist($employee);
        $this->em->flush();

        return new EmployeeIdDto($employee->getId());
    }

    public function updateEmployee(Employee $employee, UpdateEmployeePayload $payload): EmployeeDto
    {
        if ($payload->getName() && $payload->getName() !== $employee->getName()) {
            $employee->setName($payload->getName());
        }

        if ($payload->getSurname() && $payload->getSurname() !== $employee->getSurname()) {
            $employee->setSurname($payload->getSurname());
        }

        if ($payload->getEmail() && $payload->getEmail() != $employee->getEmail()) {

            if ($this->employeeRepository->findByEmail($payload->getEmail())) {
                throw new EmployeeWithEmailExists($payload->getEmail());
            }

            $employee->setEmail($payload->getEmail());
        }

        if ($payload->getBirthDate() && $payload->getBirthDate()->format('Y-m-d') != $employee->getBirthDate()->format('Y-m-d')) {
            $employee->setBirthDate($payload->getBirthDate());
        }

        if ($payload->getPesel() && $payload->getPesel() !== $employee->getPesel()) {
            $employee->setPesel($payload->getPesel());
        }

        if ($payload->getGenderId()) {
            $gender = $this->genderRepository->getGenderOrFail($payload->getGenderId());
            if ($gender !== $employee->getGender()) {
                $employee->setGender($gender);
            }
        }

        $this->em->flush();
        return EmployeeDto::fromEntity($employee);
    }

    public function deleteEmployee(Employee $employee): void
    {
        $this->em->remove($employee);
        $this->em->flush();
    }

    public function getPaginatedEmployees(
        ?EmployeeFilter $filter,
        Page            $page,
        array           $sorting
    ): PagedListDto
    {
        $qb = $this->employeeRepository->findAllQueryBuilder($filter, $sorting);
        $paginator = $this->paginator->getDoctrinePaginator($qb, $page);
        return new PagedListDto($paginator);
    }

}