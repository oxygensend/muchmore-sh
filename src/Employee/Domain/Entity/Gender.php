<?php

namespace App\Employee\Domain\Entity;

use App\Employee\Domain\Enum\GenderEnum;
use App\Employee\Infrastructure\Repository\GenderRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: GenderRepository::class)]
#[ORM\Table(name: '`employee_gender`')]
class Gender
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 10)]
    private GenderEnum $name;

    public function __construct(GenderEnum $name)
    {
        $this->name = $name;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): GenderEnum
    {
        return $this->name;
    }

    public function setName(GenderEnum $name): void
    {
        $this->name = $name;
    }

}
