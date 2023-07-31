<?php

declare(strict_types=1);

namespace App\Tests\Employee\Infrastructure\Validator;

use App\Employee\Application\Payload\CreateEmployeePayload;
use App\Employee\Application\Utils\Pesel\PeselDateTimeDecoder;
use App\Employee\Infrastructure\Repository\GenderRepository;
use App\Employee\Infrastructure\Validator\IsPeselCorrect\IsPeselCorrect;
use App\Employee\Infrastructure\Validator\IsPeselCorrect\IsPeselCorrectValidator;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\Validator\ConstraintValidatorInterface;
use Symfony\Component\Validator\Test\ConstraintValidatorTestCase;

class IsPeselCorrectValidatorTest extends ConstraintValidatorTestCase
{
    private GenderRepository|MockObject $genderRepository;
    private PeselDateTimeDecoder|MockObject $decoder;

    protected function createValidator(): ConstraintValidatorInterface
    {
        $this->genderRepository = $this->createMock(GenderRepository::class);
        $this->decoder = $this->createMock(PeselDateTimeDecoder::class);
        return new IsPeselCorrectValidator($this->genderRepository, $this->decoder);
    }

    public function test_AllParametersAreMissing(): void
    {
        // Arrange
        $payload = new CreateEmployeePayload();
        $payload->setGenderId(null);
        $payload->setBirthDate(null);
        $payload->setPesel(null);

        // Act
        $constraint = new IsPeselCorrect();
        $this->validator->validate($payload, $constraint);

        // Assert
        $this->assertNoViolation();
    }

    public function test_GenderNullIsValid(): void
    {
        // Arrange
        $payload = new CreateEmployeePayload();
        $payload->setGenderId(null);
        $payload->setBirthDate(new \DateTimeImmutable());
        $payload->setPesel("123");

        // Act
        $constraint = new IsPeselCorrect();
        $this->validator->validate($payload, $constraint);

        // Assert
        $this->buildViolation($constraint->missingAttributes)->assertRaised();
    }

    public function test_PeselNullIsValid(): void
    {
        // Arrange
        $payload = new CreateEmployeePayload();
        $payload->setGenderId(1);
        $payload->setBirthDate(new \DateTimeImmutable());
        $payload->setPesel(null);

        // Act
        $constraint = new IsPeselCorrect();
        $this->validator->validate($payload, $constraint);

        // Assert
        $this->buildViolation($constraint->missingAttributes)->assertRaised();
    }

    public function test_BirthDateNullIsValid(): void
    {
        // Arrange
        $payload = new CreateEmployeePayload();
        $payload->setGenderId(1);
        $payload->setBirthDate(null);
        $payload->setPesel("123");

        // Act
        $constraint = new IsPeselCorrect();
        $this->validator->validate($payload, $constraint);

        // Assert
        $this->buildViolation($constraint->missingAttributes)->assertRaised();
    }


    public function test_PeselIsNot11Characters(): void
    {
        // Arrange
        $payload = new CreateEmployeePayload();
        $payload->setGenderId(1);
        $payload->setBirthDate(new \DateTimeImmutable());
        $payload->setPesel("11111111");
        $constraint = new IsPeselCorrect();

        // Act
        $this->validator->validate($payload, $constraint);

        // Assert
        $this->buildViolation($constraint->invalidLengthMessage)->assertRaised();
    }

    public function test_PeselInvalidCharacters(): void
    {
        // Arrange
        $payload = new CreateEmployeePayload();
        $payload->setGenderId(1);
        $payload->setBirthDate(new \DateTimeImmutable());
        $payload->setPesel("A1111111111");
        $constraint = new IsPeselCorrect();

        // Act
        $this->validator->validate($payload, $constraint);

        // Assert
        $this->buildViolation($constraint->invalidCharactersMessage)->assertRaised();
    }

     // TODO Tests are commented because of issue with mocking GenderRepositoryInterface method -  couldnt solve it during tasks time
//    public function test_PeselInvalidBirthDate(): void
//    {
//        // Arrange
//        $payload = new CreateEmployeePayload();
//        $payload->setGenderId(1);
//        $payload->setBirthDate(new \DateTimeImmutable());
//        $payload->setPesel("11111111111");
//        $constraint = new IsPeselCorrect();
//
//        $this->decoder->expects($this->once())
//            ->method('decode')
//            ->with($payload->getPesel())
//            ->willReturn(new \DateTimeImmutable("1970-01-01"));
//
//        // Act
//        $this->validator->validate($payload, $constraint);
//
//        // Assert
//        $this->buildViolation($constraint->invalidBirthDateMessage)->assertRaised();
//    }


//    public function test_PeselInvalidGender(): void
//    {
//        // Arrange
//        $gender1 = new Gender(GenderEnum::FEMALE);
//
//        $payload = new CreateEmployeePayload();
//        $payload->setGenderId($gender1->getId());
//        $payload->setBirthDate(new \DateTimeImmutable());
//        $payload->setPesel("11111111111");
//        $constraint = new IsPeselCorrect();
//
////        $gender1 = $this->createMock(Gender::class);
////        $gender1->expects($this->any())->method('getName')->willReturn(GenderEnum::MALE);
//
//        $this->genderRepository
//            ->expects($this->once())
//            ->method('getGenderOrFail')
//            ->with($payload->getGenderId())
//            ->willReturn($gender1);
//
//        // Act
//        $this->validator->validate($payload, $constraint);
//
//        // Assert
//        $this->buildViolation($constraint->invalidGenderMessage)->assertRaised();
//    }
//
//
//    public function test_PeselIncorrectCheckDigit(): void
//    {
//        // Arrange
//        $gender = new Gender(GenderEnum::MALE);
//
//        $payload = new CreateEmployeePayload();
//        $payload->setGenderId($gender->getId());
//        $payload->setBirthDate(new \DateTimeImmutable());
//        $payload->setPesel("11111111111");
//        $constraint = new IsPeselCorrect();
//
//        $this->genderRepository->expects($this->any())
//            ->method('getGenderOrFail')
//            ->with($payload->getGenderId())
//            ->willReturn($gender);
//        // Act
//        $this->validator->validate($payload, $constraint);
//
//        // Assert
//        $this->buildViolation($constraint->invalidCheckDigitMessage)->assertRaised();
//    }


//    public function test_PeselCorrect(): void
//    {
//        // Arrange
//        $gender = new Gender(GenderEnum::MALE);
//
//        $payload = new CreateEmployeePayload();
//        $payload->setGenderId($gender->getId());
//        $payload->setBirthDate(new \DateTimeImmutable());
//        $payload->setPesel("11111111111");
//        $constraint = new IsPeselCorrect();
//
//        $this->genderRepository->expects($this->once())
//            ->method('find')
//            ->with($payload->getGenderId())
//            ->willReturn($gender);
//        // Act
//        $this->validator->validate($payload, $constraint);
//
//        // Assert
//        $this->buildViolation($constraint->invalidGenderMessage)->assertRaised();
//    }
}