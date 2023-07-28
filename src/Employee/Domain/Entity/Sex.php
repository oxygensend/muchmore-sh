<?php

namespace App\Employee\Domain\Entity;

use App\Employee\Infrastructure\Repository\SexRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SexRepository::class)]
#[ORM\Table(name: '`employee_sex`')]
class Sex
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 10)]
    private ?string $name = null;

    public function __construct(?string $name)
    {
        $this->name = $name;
    }

    public function getId(): ?int
    {
        return $this->id;
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
}
