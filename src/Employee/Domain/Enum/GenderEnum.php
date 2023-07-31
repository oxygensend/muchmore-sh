<?php

declare(strict_types=1);

namespace App\Employee\Domain\Enum;

enum GenderEnum: string
{
    case MALE = 'male';
    case FEMALE = 'female';

}
