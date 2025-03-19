<?php

declare(strict_types=1);

namespace App\DataFixtures\Factory;

use App\DataFixtures\Factory\Trait\TUniqueValue;
use App\DataFixtures\Faker\ProjectProvider;
use App\Model\Entity\Project;
use Faker\Factory;
use Zenstruck\Foundry\Persistence\PersistentProxyObjectFactory;

/**
 * @extends PersistentProxyObjectFactory<Project>
 */
final class ProjectFactory extends PersistentProxyObjectFactory
{
    use TUniqueValue;

    public static function class(): string
    {
        return Project::class;
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#model-factories
     */
    protected function defaults(): array|callable
    {
        $faker = Factory::create();
        $faker->addProvider(new ProjectProvider($faker));

        $name = self::getUniqueValue('project', $faker->projectName());
        $description = self::getUniqueValue('project', $faker->projectDescription());

        return [
            'name'        => $name,
            'description' => $description,
            'owner'       => UserFactory::random(),
        ];
    }
}
