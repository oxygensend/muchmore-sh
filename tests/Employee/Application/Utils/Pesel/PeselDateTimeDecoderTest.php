<?php

declare(strict_types=1);

namespace App\Tests\Employee\Application\Utils\Pesel;

use App\Employee\Application\Utils\Pesel\PeselDateTimeDecoder;
use PHPUnit\Framework\TestCase;

class PeselDateTimeDecoderTest extends TestCase
{

    public function test_ValidDecoding(): void
    {
        // Arrange
        $pesel = "00230920321";
        $expectedDateTime = new \DateTimeImmutable("2000-03-09");
        $decoder = new PeselDateTimeDecoder();

        // Act
        $returnedDateTime = $decoder->decode($pesel);

        // Assert
        $this->assertEquals($returnedDateTime->format('Y-m-d'), $expectedDateTime->format('Y-m-d'));
    }

}