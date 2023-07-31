<?php

namespace App\Employee\Infrastructure\Validator\IsPasswordConfirmed;

use App\Employee\Application\Payload\CreateEmployeePayload;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class IsPasswordConfirmedValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint): void
    {
        /* @var IsPasswordConfirmed $constraint */
        /* @var CreateEmployeePayload $value */

        if (null === $value || '' === $value) {
            return;
        }

        if (!$value->getConfirmPassword() || !$value->getPassword()) {
            return;
        }

        if ($value->getPassword() !== $value->getConfirmPassword()) {
            $this->context->buildViolation($constraint->message)
                ->addViolation();
        }
    }
}
