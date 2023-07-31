<?php

declare(strict_types=1);

namespace App\Employee\Application\Utils\Pesel;


class PeselDateTimeDecoder
{

    private readonly array $dateTimesOffset;

    public function __construct()
    {
        $this->dateTimesOffset = [
            new DateTimeOffset(80, 1800),
            new DateTimeOffset(60, 2200),
            new DateTimeOffset(40, 2100),
            new DateTimeOffset(20, 2000),
            new DateTimeOffset(0, 1900),
        ];
    }


    public function decode(string $pesel): \DateTimeImmutable
    {
        $birthYear = intval(substr($pesel, 0, 2));
        $birthMonth = intval(substr($pesel, 2, 2));
        $birthDay = intval(substr($pesel, 4, 2));

        /** @var ?DateTimeOffset $offset */
        $offset = null;
        foreach ($this->dateTimesOffset as $dt) {
            if ($dt->monthOffset <= $birthMonth) {
                $offset = $dt;
                break;
            }
        }

        $birthYear += $offset->yearOffset;
        $month = $birthMonth - $offset->monthOffset;

        return (new \DateTimeImmutable())->setDate($birthYear, $month, $birthDay);
    }
}