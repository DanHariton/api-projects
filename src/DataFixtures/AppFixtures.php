<?php

namespace App\DataFixtures;

use App\DataFixtures\Factory\ProjectFactory;
use App\DataFixtures\Factory\UserFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

final class AppFixtures extends Fixture
{
    public function __construct(
        private readonly int $smallCount,
        private readonly int $largeCount
    ) {
    }

    public function load(ObjectManager $manager): void
    {
        UserFactory::createMany($this->smallCount);
        ProjectFactory::createMany($this->largeCount);

        $manager->flush();
    }
}
