<?php

declare(strict_types=1);

namespace App\Employee\Infrastructure\DataFixtures;

use App\Employee\Domain\Entity\Gender;
use App\Employee\Domain\Enum\GenderEnum;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\SharedFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class GenderFixture extends Fixture implements SharedFixtureInterface
{

    public function load(ObjectManager $manager): void
    {
        $sex = new Gender(GenderEnum::MALE);
        $manager->persist($sex);
        $this->addReference('gender_male', $sex);

        $sex2 = new Gender(GenderEnum::FEMALE);
        $manager->persist($sex2);
        $this->addReference('gender_female', $sex2);

        $manager->flush();
    }
}