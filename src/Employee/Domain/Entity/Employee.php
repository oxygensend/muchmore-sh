<?php

namespace App\Employee\Domain\Entity;

use App\Employee\Infrastructure\Repository\EmployeeRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: EmployeeRepository::class)]
#[ORM\Table(name: '`employee_employee`')]
class Employee implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    private ?string $email = null;

    #[ORM\Column]
    private array $roles = [];

    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column(length: 64)]
    private ?string $name = null;

    #[ORM\Column(length: 64)]
    private ?string $surname = null;

    #[ORM\Column(length: 11)]
    private ?string $pesel = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $birthDate = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Sex $sex = null;

    /**
     * @param string|null $email
     * @param array $roles
     * @param string|null $password
     * @param string|null $name
     * @param string|null $surname
     * @param string|null $pesel
     * @param \DateTimeImmutable|null $birthDate
     * @param Sex|null $sex
     */
    public function __construct(
        ?string $email,
        ?string $name,
        ?string $surname,
        ?string $pesel,
        ?\DateTimeImmutable $birthDate,
        ?Sex $sex
    ) {
        $this->email = $email;
        $this->name = $name;
        $this->surname = $surname;
        $this->pesel = $pesel;
        $this->birthDate = $birthDate;
        $this->sex = $sex;
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }


    public function getUserIdentifier(): string
    {
        return (string)$this->email;
    }

    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }


    public function eraseCredentials(): void
    {
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getSurname(): ?string
    {
        return $this->surname;
    }

    public function setSurname(string $surname): static
    {
        $this->surname = $surname;

        return $this;
    }

    public function getPesel(): ?string
    {
        return $this->pesel;
    }

    public function setPesel(string $pesel): static
    {
        $this->pesel = $pesel;

        return $this;
    }

    public function getBirthDate(): ?\DateTimeImmutable
    {
        return $this->birthDate;
    }

    public function setBirthDate(\DateTimeImmutable $birthDate): static
    {
        $this->birthDate = $birthDate;

        return $this;
    }

    public function getSex(): ?Sex
    {
        return $this->sex;
    }

    public function setSex(?Sex $sex): static
    {
        $this->sex = $sex;

        return $this;
    }
}
