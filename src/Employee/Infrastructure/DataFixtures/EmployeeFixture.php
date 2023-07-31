<?php

declare(strict_types=1);

namespace App\Employee\Infrastructure\DataFixtures;

use App\Employee\Domain\Entity\Employee;
use App\Employee\Domain\Entity\Gender;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class EmployeeFixture extends Fixture implements DependentFixtureInterface
{

    public function __construct(private readonly UserPasswordHasherInterface $passwordHasher)
    {
    }

    public function getDependencies(): array
    {
        return [GenderFixture::class];
    }

    public function load(ObjectManager $manager): void
    {
        $start = new DateTime('1970-01-01');
        $end = new DateTime('2020-01-01');
        for ($i = 1; $i < 101; $i++) {
            $email = "test" . $i . "@test.com";
            $name = "TestName" . $i;
            $surname = "TestSurname" . $i;
            /** @var Gender $sex */
            $sex = $this->getReference($i % 2 ? 'gender_male' : 'gender_female');
            $employee = new Employee(
                $email,
                $name,
                $surname,
                $this->randomPesel(),
                $this->randomBrithDate($start, $end),
                $sex
            );

            $password = $this->passwordHasher->hashPassword($employee, 'test123');
            $employee->setPassword($password);

            $manager->persist($employee);
        }

        $manager->flush();
    }

    private function randomBrithDate(DateTime $start, DateTime $end): \DateTimeImmutable
    {
        $randomTimestamp = mt_rand($start->getTimestamp(), $end->getTimestamp());
        $randomDate = new \DateTimeImmutable();
        $randomDate->setTimestamp($randomTimestamp);
        return $randomDate;
    }

    private function randomPesel(): string
    {
        return substr(str_shuffle('0123456789'), 1, 11);
    }
}