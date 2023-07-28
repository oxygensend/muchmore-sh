<?php

declare(strict_types=1);

namespace App\Employee\Infrastructure\DataFixtures;

use App\Employee\Domain\Entity\Sex;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\SharedFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class SexFixture extends Fixture implements SharedFixtureInterface
{

    public function load(ObjectManager $manager): void
    {
        $sex = new Sex("male");
        $manager->persist($sex);
        $this->addReference('sex_male', $sex);

        $sex2 = new Sex("female");
        $manager->persist($sex2);
        $this->addReference('sex_female', $sex2);

        $manager->flush();
    }
}