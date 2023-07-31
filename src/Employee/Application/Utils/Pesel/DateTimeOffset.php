<?php

declare(strict_types=1);

namespace App\Employee\Application\Utils\Pesel;

readonly class DateTimeOffset
{
    public function __construct(
        public int $monthOffset,
        public int $yearOffset
    ) {
    }

}