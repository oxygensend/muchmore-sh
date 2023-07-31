<?php

declare(strict_types=1);

namespace App\Employee\Application\Payload;

use App\Employee\Infrastructure\Validator\IsPeselCorrect\IsPeselCorrect;
use Symfony\Component\Validator\Constraints as Assert;

#[IsPeselCorrect]
class UpdateEmployeePayload
{
    #[Assert\Length(min: 2, max: 64)]
    private ?string $name = null;

    #[Assert\Length(min: 2, max: 64)]
    private ?string $surname = null;

    #[Assert\Email]
    private ?string $email = null;

    private ?string $pesel = null;

    private ?\DateTimeImmutable $birthDate = null;

    private ?int $genderId = null;

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    public function getSurname(): ?string
    {
        return $this->surname;
    }

    public function setSurname(?string $surname): void
    {
        $this->surname = $surname;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): void
    {
        $this->email = $email;
    }

    public function getPesel(): ?string
    {
        return $this->pesel;
    }

    public function setPesel(?string $pesel): void
    {
        $this->pesel = $pesel;
    }

    public function getBirthDate(): ?\DateTimeImmutable
    {
        return $this->birthDate;
    }

    public function setBirthDate(?\DateTimeImmutable $birthDate): void
    {
        $this->birthDate = $birthDate;
    }

    public function getGenderId(): ?int
    {
        return $this->genderId;
    }

    public function setGenderId(?int $genderId): void
    {
        $this->genderId = $genderId;
    }

}