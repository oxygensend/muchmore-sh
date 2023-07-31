<?php

namespace App\Employee\Infrastructure\Validator\IsPeselCorrect;

use App\Employee\Application\Payload\CreateEmployeePayload;
use App\Employee\Application\Utils\Pesel\PeselDateTimeDecoder;
use App\Employee\Domain\Enum\GenderEnum;
use App\Employee\Domain\Repository\GenderRepositoryInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class IsPeselCorrectValidator extends ConstraintValidator
{

    const WEIGHTS = [1, 3, 7, 9, 1, 3, 7, 9, 1, 3];


    public function __construct(
        private readonly GenderRepositoryInterface $genderRepository,
        private readonly PeselDateTimeDecoder      $peselDateTimeDecoder
    )
    {
    }

    public function validate($value, Constraint $constraint): void
    {
        /* @var IsPeselCorrect $constraint */
        /* @var CreateEmployeePayload $value */

        if (null === $value || '' === $value) {
            return;
        }

        $pesel = $value->getPesel();
        $birthDate = $value->getBirthDate();
        $genderId = $value->getGenderId();

        if (($pesel && (!$genderId || !$birthDate) || ($genderId && (!$pesel || !$birthDate)) || ($birthDate && (!$genderId || !$pesel)))) {
            $this->context->buildViolation($constraint->missingAttributes)->addViolation();
            return;
        }
        if (!$pesel || !$genderId || !$birthDate) {
            return;
        }

        if (strlen($pesel) !== 11) {
            $this->context->buildViolation($constraint->invalidLengthMessage)
                ->addViolation();
            return;
        }

        if (!preg_match('/^\d+$/', $pesel)) {
            $this->context->buildViolation($constraint->invalidCharactersMessage)
                ->addViolation();
            return;
        }

        if (!$this->hasValidBirthDate($pesel, $birthDate)) {
            $this->context->buildViolation($constraint->invalidBirthDateMessage)
                ->addViolation();
        }

        if (!$this->hasValidGender($pesel, $genderId)) {
            $this->context->buildViolation($constraint->invalidGenderMessage)
                ->addViolation();
        }

        if (!$this->hasCorrectCheckDigit($pesel)) {
            $this->context->buildViolation($constraint->invalidCheckDigitMessage)
                ->addViolation();
        }
    }

    private function hasValidBirthDate(string $pesel, \DateTimeImmutable $birthDate): bool
    {
        $decodedBirthDate = $this->peselDateTimeDecoder->decode($pesel);
        return $birthDate->format('Y-m-d') === $decodedBirthDate->format('Y-m-d');
    }

    private function hasValidGender(string $pesel, int $genderId): bool
    {
        $gender = $this->genderRepository->getGenderOrFail($genderId);
        $genderDigit = intval(substr($pesel, 9, 1));
        $isMale = $genderDigit % 2 === 1;
        return !(($gender->getName() === GenderEnum::MALE && !$isMale) || ($gender->getName() === GenderEnum::FEMALE && $isMale));

    }

    private function hasCorrectCheckDigit(string $pesel): bool
    {
        $checkDigit = intval(substr($pesel, 10, 1));
        $sum = 0;

        for ($i = 0; $i < 10; $i++) {
            $sum += $pesel[$i] * self::WEIGHTS[$i];
        }

        $checkSum = 10 - ($sum % 10);
        $checkSum = $checkSum === 10 ? 0 : $checkSum;

        return !($checkDigit != $checkSum);
    }
}
