<?php

namespace App\Employee\Infrastructure\Validator\IsPeselCorrect;

use Symfony\Component\Validator\Constraint;


/**
 * @Annotation
 * @Target({"CLASS"})
 */
#[\Attribute(\Attribute::TARGET_CLASS | \Attribute::IS_REPEATABLE)]
class IsPeselCorrect extends Constraint
{
    /*
     * Any public properties become valid options for the annotation.
     * Then, use these in your validator class.
     */

    public string $invalidLengthMessage = 'Pesel should have 11 digits';
    public string $invalidCharactersMessage = 'Pesel should have only digits';
    public string $invalidBirthDateMessage = 'Pesel birth date is invalid';
    public string $invalidGenderMessage = 'Pesel gender is invalid';
    public string $invalidCheckDigitMessage = 'Pesel has invalid check digit';

    public string $missingAttributes = 'Setting pesel requires date of birth, gender and pesel';

    public function getTargets(): array|string
    {
        return self::CLASS_CONSTRAINT;
    }
}
