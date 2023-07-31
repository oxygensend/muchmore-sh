<?php
declare(strict_types=1);

namespace App\Tests\Employee\Domain\Service;

use App\Commons\Pagination\Factory\PaginatorFactoryInterface;
use App\Employee\Application\Dto\EmployeeDto;
use App\Employee\Application\Dto\EmployeeIdDto;
use App\Employee\Application\Payload\CreateEmployeePayload;
use App\Employee\Application\Payload\UpdateEmployeePayload;
use App\Employee\Domain\Entity\Employee;
use App\Employee\Domain\Entity\Gender;
use App\Employee\Domain\Enum\GenderEnum;
use App\Employee\Domain\Exception\EmployeeWithEmailExists;
use App\Employee\Domain\Repository\EmployeeRepositoryInterface;
use App\Employee\Domain\Repository\GenderRepositoryInterface;
use App\Employee\Domain\Service\EmployeeService;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class EmployeeServiceTest extends TestCase
{

    private GenderRepositoryInterface|MockObject $genderRepository;
    private EmployeeRepositoryInterface|MockObject $employeeRepository;
    private EntityManagerInterface|MockObject $entityManager;
    private UserPasswordHasherInterface|MockObject $passwordHasher;
    private PaginatorFactoryInterface|MockObject $paginator;

    private EmployeeService $service;

    protected function setUp(): void
    {
        $this->genderRepository = $this->createMock(GenderRepositoryInterface::class);
        $this->employeeRepository = $this->createMock(EmployeeRepositoryInterface::class);
        $this->entityManager = $this->createMock(EntityManagerInterface::class);
        $this->passwordHasher = $this->createMock(UserPasswordHasherInterface::class);
        $this->paginator = $this->createMock(PaginatorFactoryInterface::class);
        $this->service = new EmployeeService($this->genderRepository, $this->employeeRepository, $this->passwordHasher, $this->entityManager, $this->paginator);
    }

    public function test_CreateEmployee(): void
    {
        // Arrange
        $payload = new CreateEmployeePayload();
        $payload->setName("test");
        $payload->setSurname("test");
        $payload->setEmail("test");
        $payload->setPassword("test");
        $payload->setConfirmPassword("test");
        $payload->setBirthDate(new \DateTimeImmutable());
        $payload->setPesel("00323121111");
        $payload->setGenderId(1);
        $gender = new Gender(GenderEnum::FEMALE);

        $this->genderRepository
            ->expects($this->once())
            ->method('getGenderOrFail')
            ->with($payload->getGenderId())
            ->willReturn($gender);
        $this->entityManager
            ->expects($this->once())
            ->method('persist')
            ->willReturnCallback(function (Employee $employee) {
                $employee->setId(1);
            });

        // Act
        $employeeIdDto = $this->service->createEmployee($payload);

        // Assert
        $this->assertInstanceOf(EmployeeIdDto::class, $employeeIdDto);
    }

    public function test_CreateEmployeeEmailExists(): void
    {
        // Arrange
        $payload = new CreateEmployeePayload();
        $payload->setName("test");
        $payload->setSurname("test");
        $payload->setEmail("test");
        $payload->setPassword("test");
        $payload->setConfirmPassword("test");
        $payload->setBirthDate(new \DateTimeImmutable());
        $payload->setPesel("00323121111");
        $payload->setGenderId(1);

        $employee = $this->createMock(Employee::class);
        $this->employeeRepository
            ->expects($this->once())
            ->method('findByEmail')
            ->with($payload->getEmail())
            ->willReturn($employee);

        // Act && assert
        $this->expectException(EmployeeWithEmailExists::class);
        $employeeIdDto = $this->service->createEmployee($payload);

    }

    public function test_UpdateEmployee(): void
    {
        // Arrange
        $gender = new Gender(GenderEnum::FEMALE);
        $gender1 = new Gender(GenderEnum::MALE);
        $employee = new Employee("test", "test", "test", "00232323232", new \DateTimeImmutable(), $gender);
        $employee->setId(1);

        $payload = new UpdateEmployeePayload();
        $payload->setName("test1");
        $payload->setSurname("test1");
        $payload->setEmail("test1");
        $payload->setBirthDate(new \DateTimeImmutable('1900-01-01'));
        $payload->setPesel("00323121111");
        $payload->setGenderId(1);

        $this->genderRepository
            ->expects($this->once())
            ->method('getGenderOrFail')
            ->with($payload->getGenderId())
            ->willReturn($gender1);

        $employeeDtoExpected = new EmployeeDto(
            $employee->getId(),
            $payload->getName(),
            $payload->getSurname(),
            $payload->getEmail(),
            $payload->getPesel(),
            $payload->getBirthDate(),
            $gender1->getName()
        );

        // Act
        $employeeDtoReturned = $this->service->updateEmployee($employee, $payload);

        // Assert
        $this->assertInstanceOf(EmployeeDto::class, $employeeDtoReturned);
        $this->assertEquals($employeeDtoExpected, $employeeDtoReturned);

    }


    public function test_UpdateEmployeeEmailExists(): void
    {
        // Arrange
        $gender = new Gender(GenderEnum::FEMALE);
        $employee = new Employee("test", "test", "test", "00232323232", new \DateTimeImmutable(), $gender);
        $employee->setId(1);

        $payload = new UpdateEmployeePayload();
        $payload->setName("test1");
        $payload->setSurname("test");
        $payload->setEmail("test1");
        $payload->setBirthDate(new \DateTimeImmutable('1900-01-01'));
        $payload->setPesel("00323121111");
        $payload->setGenderId(1);
        $this->employeeRepository
            ->expects($this->once())
            ->method('findByEmail')
            ->with($payload->getEmail())
            ->willReturn($employee);

        // Act && assert
        $this->expectException(EmployeeWithEmailExists::class);
        $this->service->updateEmployee($employee, $payload);

    }

    public function test_DeleteEmployee(): void
    {
        // Arrange
        $gender = new Gender(GenderEnum::FEMALE);
        $employee = new Employee("test", "test", "test", "00232323232", new \DateTimeImmutable(), $gender);

        // Act && Assert
        $this->entityManager
            ->expects($this->once())
            ->method('remove')
            ->with($employee);

        $this->entityManager
            ->expects($this->once())
            ->method('flush');

        $this->service->deleteEmployee($employee);
    }

    public function test_GetEmployee(): void
    {
        $gender = new Gender(GenderEnum::FEMALE);
        $employee = new Employee("test", "test", "test", "00232323232", new \DateTimeImmutable(), $gender);
        $employee->setId(1);
        $employeeDtoExpected = new EmployeeDto(
            $employee->getId(),
            $employee->getName(),
            $employee->getSurname(),
            $employee->getEmail(),
            $employee->getPesel(),
            $employee->getBirthDate(),
            $employee->getGender()->getName()
        );

        $employeeDtoReturned = $this->service->getEmployee($employee);

        $this->assertInstanceOf(EmployeeDto::class, $employeeDtoReturned);
        $this->assertEquals($employeeDtoExpected, $employeeDtoReturned);

    }
}