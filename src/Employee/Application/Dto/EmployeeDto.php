<?php

declare(strict_types=1);

namespace App\Employee\Application\Dto;

use App\Employee\Domain\Entity\Employee;
use App\Employee\Domain\Enum\GenderEnum;

readonly class EmployeeDto
{
    public function __construct(
        public int                $id,
        public string             $name,
        public string             $surname,
        public string             $email,
        public string             $pesel,
        public \DateTimeImmutable $birthDate,
        public GenderEnum         $gender
    )
    {
    }

    static public function fromEntity(Employee $employee): self
    {
        return new self(
            $employee->getId(),
            $employee->getName(),
            $employee->getSurname(),
            $employee->getEmail(),
            $employee->getPesel(),
            $employee->getBirthDate(),
            $employee->getGender()->getName()
        );
    }
}