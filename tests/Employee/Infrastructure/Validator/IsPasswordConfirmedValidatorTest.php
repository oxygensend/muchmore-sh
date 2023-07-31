<?php

declare(strict_types=1);

namespace App\Tests\Employee\Infrastructure\Validator;

use App\Employee\Application\Payload\CreateEmployeePayload;
use App\Employee\Infrastructure\Validator\IsPasswordConfirmed\IsPasswordConfirmed;
use App\Employee\Infrastructure\Validator\IsPasswordConfirmed\IsPasswordConfirmedValidator;
use Symfony\Component\Validator\ConstraintValidatorInterface;
use Symfony\Component\Validator\Test\ConstraintValidatorTestCase;

class IsPasswordConfirmedValidatorTest extends ConstraintValidatorTestCase
{

    protected function createValidator(): ConstraintValidatorInterface
    {
        return new IsPasswordConfirmedValidator();
    }

    public function test_PasswordNullIsValid(): void
    {
        // Arrange
        $payload = new CreateEmployeePayload();
        $payload->setPassword(null);
        $payload->setConfirmPassword("x");

        // Act
        $this->validator->validate($payload, new IsPasswordConfirmed());

        // Assert
        $this->assertNoViolation();
    }


    public function test_ConfirmPasswordNullIsValid(): void
    {
        // Arrange
        $payload = new CreateEmployeePayload();
        $payload->setPassword("x");
        $payload->setConfirmPassword(null);

        // Act
        $this->validator->validate($payload, new IsPasswordConfirmed());

        // Assert
        $this->assertNoViolation();
    }


    public function test_EmptyPasswordNullIsValid(): void
    {
        // Arrange
        $payload = new CreateEmployeePayload();
        $payload->setPassword("");
        $payload->setConfirmPassword("");

        // Act
        $this->validator->validate($payload, new IsPasswordConfirmed());

        // Assert
        $this->assertNoViolation();
    }

    public function test_PasswordNotConfirmed(): void
    {
        // Arrange
        $payload = new CreateEmployeePayload();
        $payload->setPassword("test123");
        $payload->setConfirmPassword("321tset");

        // Act
        $constraint = new IsPasswordConfirmed();
        $this->validator->validate($payload, $constraint);

        // Assert
        $this->buildViolation($constraint->message)
            ->assertRaised();
    }

    public function test_PasswordConfirmed(): void
    {
        // Arrange
        $payload = new CreateEmployeePayload();
        $payload->setPassword("test123");
        $payload->setConfirmPassword("test123");

        // Act
        $constraint = new IsPasswordConfirmed();
        $this->validator->validate($payload, $constraint);

        // Assert
        $this->assertNoViolation();
    }


}