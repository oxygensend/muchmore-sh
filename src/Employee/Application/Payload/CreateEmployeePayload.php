<?php

declare(strict_types=1);

namespace App\Employee\Application\Payload;

use App\Employee\Infrastructure\Validator\IsPasswordConfirmed\IsPasswordConfirmed;
use App\Employee\Infrastructure\Validator\IsPeselCorrect\IsPeselCorrect;
use Symfony\Component\Validator\Constraints as Assert;

#[IsPasswordConfirmed]
#[IsPeselCorrect]
class CreateEmployeePayload
{

    #[Assert\NotBlank]
    #[Assert\Length(min: 2, max: 64)]
    private string $name;

    #[Assert\NotBlank]
    #[Assert\Length(min: 2, max: 64)]
    private string $surname;

    #[Assert\NotBlank]
    #[Assert\Email]
    private string $email;

    #[Assert\NotBlank]
    #[Assert\PasswordStrength(minScore: Assert\PasswordStrength::STRENGTH_MEDIUM)]
    private ?string $password = null;

    #[Assert\NotBlank]
    private ?string $confirmPassword = null;

    #[Assert\NotBlank]
    private ?string $pesel = null;

    #[Assert\NotBlank]
    private ?\DateTimeImmutable $birthDate = null;

    #[Assert\NotBlank]
    private ?int $genderId = null;


    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getSurname(): string
    {
        return $this->surname;
    }

    public function setSurname(string $surname): void
    {
        $this->surname = $surname;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(?string $password): void
    {
        $this->password = $password;
    }

    public function getConfirmPassword(): ?string
    {
        return $this->confirmPassword;
    }

    public function setConfirmPassword(?string $confirmPassword): void
    {
        $this->confirmPassword = $confirmPassword;
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