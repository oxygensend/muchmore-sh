<?php

declare(strict_types=1);

namespace App\Employee\Application\Controller;

use App\Commons\Pagination\Dto\PagedListDto;
use App\Commons\Pagination\Utils\Page;
use App\Employee\Application\Dto\EmployeeDto;
use App\Employee\Application\Dto\EmployeeIdDto;
use App\Employee\Application\Filter\EmployeeFilter;
use App\Employee\Application\Payload\CreateEmployeePayload;
use App\Employee\Application\Payload\UpdateEmployeePayload;
use App\Employee\Domain\Entity\Employee;
use App\Employee\Domain\Service\EmployeeService;
use Nelmio\ApiDocBundle\Annotation\Model;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Annotation\Route;
use OpenApi\Attributes as OA;


#[OA\Tag("Employee")]
#[OA\Response(
    response: 401,
    description: "JWT Token not found"
)]
#[Route('/api/employees')]
class EmployeeController extends AbstractController
{

    public function __construct(private readonly EmployeeService $employeeService)
    {
    }

    #[OA\Response(
        response: 200,
        description: 'Returns the paginated employees',
        content: new Model(type: PagedListDto::class)
    )]
    #[OA\QueryParameter(name: 'page', description: "Current page", in: 'query', example: 1)]
    #[OA\QueryParameter(name: 'length', description: "Amount of parameters on each site", in: 'query', example: 10)]
    #[OA\QueryParameter(name: 'sorting[0][column]', description: "Sorting(name, surname, email, birthDate, gender)", in: 'query', example: "name")]
    #[OA\QueryParameter(name: 'sorting[0][dir]', description: "Sorting(asc, desc)", in: 'query', example: "asc")]
    #[OA\QueryParameter(name: 'filters[name]', description: "Filter by name", in: 'query')]
    #[OA\QueryParameter(name: 'filters[surname]', description: "Filter by surname", in: 'query')]
    #[OA\QueryParameter(name: 'filters[email]', description: "Filter by email", in: 'query')]
    #[OA\QueryParameter(name: 'filters[birthDateLt]', description: "< brithDate", in: 'query')]
    #[OA\QueryParameter(name: 'filters[birthDateGt]', description: "> birthDate", in: 'query')]
    #[OA\QueryParameter(name: 'filters[search]', description: "Search in fields(nane, surname, email, pesel)", in: 'query')]
    #[OA\QueryParameter(name: 'filters[ids][]', description: "Filter by  ids", in: 'query', schema: new OA\Schema(type: 'array', items: new OA\Items(type: "int")))]
    #[OA\QueryParameter(name: 'filters[genderIds][]', description: "Filter by gender ids", in: 'query', schema: new OA\Schema(type: 'array', items: new OA\Items(type: "int")))]
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

    #[OA\RequestBody(content: new Model(type: CreateEmployeePayload::class))]
    #[OA\Response(
        response: 201,
        description: "Create employee",
        content: new Model(type: EmployeeIdDto::class)
    )]
    #[OA\Response(
        response: 400,
        description: "Bad request exeption",
    )]
    #[OA\Response(
        response: 422,
        description: "Violation list"
    )]
    #[Route('', methods: ['POST'])]
    public function create(#[MapRequestPayload] CreateEmployeePayload $payload): Response
    {
        return $this->json($this->employeeService->createEmployee($payload), Response::HTTP_CREATED);
    }


    #[OA\RequestBody(content: new Model(type: UpdateEmployeePayload::class))]
    #[OA\Response(
        response: 200,
        description: "Update employee data",
        content: new Model(type: EmployeeDto::class)
    )]
    #[OA\Response(
        response: 400,
        description: "Bad request exeption",
    )]
    #[OA\Response(
        response: 422,
        description: "Violation list"
    )]
    #[Route('/{id}', methods: ['PATCH'])]
    public function update(Employee $employee, #[MapRequestPayload] UpdateEmployeePayload $payload): Response
    {
        return $this->json($this->employeeService->updateEmployee($employee, $payload), Response::HTTP_OK);
    }

    #[OA\Response(
        response: 200,
        description: "Fetch employee by id",
        content: new Model(type: EmployeeDto::class)
    )]
    #[OA\Response(
        response: 404,
        description: "Employee not found"
    )]
    #[Route('/{id}', methods: ['GET'])]
    public function show(Employee $employee): Response
    {
        return $this->json($this->employeeService->getEmployee($employee), Response::HTTP_OK);
    }

    #[OA\Response(
        response: 204,
        description: "Remove employee from database",
    )]
    #[Route('/{id}', methods: ['DELETE'])]
    public function delete(Employee $employee): Response
    {
        $this->employeeService->deleteEmployee($employee);
        return $this->json([], Response::HTTP_NO_CONTENT);
    }
}