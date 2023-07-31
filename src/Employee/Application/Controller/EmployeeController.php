<?php

declare(strict_types=1);

namespace App\Employee\Application\Controller;

use App\Commons\Pagination\Utils\Page;
use App\Employee\Application\Filter\EmployeeFilter;
use App\Employee\Application\Payload\CreateEmployeePayload;
use App\Employee\Application\Payload\UpdateEmployeePayload;
use App\Employee\Domain\Entity\Employee;
use App\Employee\Domain\Service\EmployeeService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Annotation\Route;


#[Route('/api/employees')]
class EmployeeController extends AbstractController
{

    public function __construct(private readonly EmployeeService $employeeService)
    {
    }

    #[Route('', methods: ['GET'])]
    public function getAll(
        #[MapQueryParameter] ?int  $page = 1,
        #[MapQueryParameter] ?int  $length = 10,
        #[MapQueryParameter] array $sorting = [],
        #[MapQueryParameter] array $filters = []
    ): Response
    {
        return $this->json(
            $this->employeeService->getPaginatedEmployees(
                EmployeeFilter::fromQuery($filters),
                new Page($page, $length),
                $sorting
            ),
            Response::HTTP_OK
        );
    }

    #[Route('', methods: ['POST'])]
    public function create(#[MapRequestPayload] CreateEmployeePayload $payload): Response
    {
        return $this->json($this->employeeService->createEmployee($payload), Response::HTTP_CREATED);
    }

    #[Route('/{id}', methods: ['PATCH'])]
    public function update(Employee $employee, #[MapRequestPayload] UpdateEmployeePayload $payload): Response
    {
        return $this->json($this->employeeService->updateEmployee($employee, $payload), Response::HTTP_OK);
    }

    #[Route('/{id}', methods: ['GET'])]
    public function show(Employee $employee): Response
    {
        return $this->json($this->employeeService->getEmployee($employee), Response::HTTP_OK);
    }

    #[Route('/{id}', methods: ['DELETE'])]
    public function delete(Employee $employee): Response
    {
        $this->employeeService->deleteEmployee($employee);
        return $this->json([], Response::HTTP_NO_CONTENT);
    }
}